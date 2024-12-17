<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Commande;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

//use Illuminate\Support\Facades\Notification;
//use App\Notifications\CommandeNotification;


class CommandeController extends Controller
{
    public function index()
    {
//        $commande = Commande::all();
//        return response()->json($commande, 200);
        $commande=Commande::with('client', 'burger')->get();
        return response()->json($commande,200);
    }

    /**
     * Store a newly created resource in storage.
     */
//    public function store(Request $request)
//    {
//        $valid = $request->validate([
//            'client' => 'required|array',
//            'client.nom' => 'required|string',
//            'client.prenom' => 'required|string',
//            'client.telephone' => 'required|string',
//            'client.email' => 'required|email',
//            'dateCommande' => 'required|date',
//            'etat' => 'required|string',
//            'montantTotal' => 'required|numeric|min:1',
//            'quantite' => 'required|numeric|min:1',
//            'burger_id' => 'required|integer',
//        ]);
//        // Récupérer ou créer un client
//        $clientData = $valid['client'];
//        $client = Client::firstOrCreate(
//            ['email' => $clientData['email']], // Condition d'unicité
//            $clientData // Données de création
//        );
//        // Créer la commande
//        $commandeData = $valid;
//        $commandeData['client_id'] = $client->id;
////        dd($commandeData);
//        $commande = Commande::create($commandeData);
//
//        // Notification par email
////        Notification::route('mail', $client->email)
////            ->notify(new CommandeNotification($commande));
//
//        return response()->json($commande, 201);
//    }
    public function store(Request $request)  {
//        dd($request->all());
        // Valider la requête
        $request->validate([
            'client.nom' => 'required|string|max:255',
            'client.prenom' => 'required|string|max:255',
            'client.telephone' => 'required|string|max:255',
            'client.email' => 'required|string|max:255',
            'dateCommande' => 'required|date',
            'etat' => 'required|string',
            'montantTotal' => 'required|integer|min:1',
            'quantite' => 'required|integer|min:1',
            'burger_id' => 'required|exists:burgers,id',
        ]);

        // Extraire les informations du client
        $clientData = $request->input('client');
        $burgerId = $request->input('burger_id');
        $quantite = $request->input('quantite');
        $dateCommande = $request->input('dateCommande');
        $montantTotal=$request->input('montantTotal');

        // Vérifier si le client existe déjà
        $client = Client::where('nom', $clientData['nom'])
            ->where('prenom', $clientData['prenom'])
            ->where('telephone', $clientData['telephone'])
            ->where('email', $clientData['email'])
            ->first();

        // Si le client n'existe pas, le créer
        if (!$client) {
            $client = Client::create($clientData);
        }

        // Créer la commande associée au client
        $commande = Commande::create([
            'client_id' => $client->id,
            'burger_id' => $burgerId,
            'quantite' => $quantite,
            'statut' => 'en attente',
            'dateCommande'=>$dateCommande,
            'montantTotal'=>$montantTotal,
        ]);


        return response()->json($commande, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $commande = Commande::find($id);
        if (!$commande) {
            return response()->json(['message' => 'Commande not found'], 404);
        }
        return response()->json($commande, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $commande = Commande::find($id);
        if (!$commande) {
            return response()->json(['message' => 'Commande not found'], 404);
        }

        $valid = $request->validate([
            'client' => 'sometimes|required|array',
            'client.nom' => 'sometimes|required|string',
            'client.prenom' => 'sometimes|required|string',
            'client.telephone' => 'sometimes|required|string',
            'client.email' => 'sometimes|required|email',
            'dateCommande' => 'sometimes|required|date',
            'etat' => 'sometimes|required|string',
            'montantTotal' => 'sometimes|required|numeric|min:1',
            'burger_id' => 'sometimes|required|integer',
        ]);

        if (isset($valid['client'])) {
            $clientData = $valid['client'];
            $client = Client::updateOrCreate(
                ['email' => $clientData['email']], // Condition d'unicité
                $clientData // Données de mise à jour
            );
            $commande->client_id = $client->id;
        }

        $commande->update($valid);

        return response()->json($commande, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $commande = Commande::find($id);
        if (!$commande) {
            return response()->json(['message' => 'Commande not found'], 404);
        }

        $commande->delete();
        return response()->json(['message' => 'Commande deleted'], 200);
    }

    /**
     * Cancel the specified resource.
     */
    public function cancel(Request $request, Commande $commande)
    {
        if ($commande->etat !== 'en attente') {
            return response()->json(['message' => 'La commande ne peut pas être annulée'], 400);
        }

        // Mettre à jour le statut de la commande
        $commande->etat = 'Annuler';
        $commande->save();

        return response()->json(['message' => 'Commande annulée avec succès'], 200);
    }

//     Valider la commande
    public function valider($id)
    {
//        $commande = Commande::find($id);
        $commande = Commande::with('client', 'burger')->find($id);
        if ($commande) {
            $commande->etat = 'Validée'; // Met à jour l'état de la commande
            $commande->save();
            // Generate the PDF
            $pdf = PDF::loadView('emails.commande', ['order' => $commande]);

            // Send the email with the PDF attachment
            Mail::send([], [], function ($message) use ($commande, $pdf) {
                $message->to($commande->client->email)
                    ->subject('Votre commande est terminée')
                    ->attachData($pdf->output(), 'commande.pdf', [
                        'mime' => 'application/pdf',
                    ]);
            });

            return response()->json(['message' => 'Commande validée'], 200);

        } else {
            return response()->json(['message' => 'Commande non trouvée'], 404);
        }
    }
//    public function valider($id)
//    {
//        $commande = Commande::find($id);
//        if ($commande) {
//            $commande->etat = 'Validée'; // Met à jour l'état de la commande
//            $commande->save();
//
//            // Generate the PDF
//            $pdf = PDF::loadView('emails.commande', ['order' => $commande]);
//
//            // Send the email with the PDF attachment
//            try {
//                Mail::send([], [], function ($message) use ($commande, $pdf) {
//                    $message->to($commande->client->email)
//                        ->subject('Votre commande est terminée')
//                        ->attachData($pdf->output(), 'commande.pdf', [
//                            'mime' => 'application/pdf',
//                        ]);
//                });
//            } catch (\Exception $e) {
//                Log::error('Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
//                return response()->json(['message' => 'Erreur lors de l\'envoi de l\'email'], 500);
//            }
//
//            return response()->json(['message' => 'Commande validée'], 200);
//        } else {
//            return response()->json(['message' => 'Commande non trouvée'], 404);
//        }
//    }

    // Envoyer l'email
//    public function envoyerEmail($id)
//    {
//        $commande = Commande::find($id);
//
//        if ($commande) {
//            Mail::to($commande->client->email)->send(new CommandeValidéeMail($commande));
//
//            return response()->json(['message' => 'Email envoyé'], 200);
//        } else {
//            return response()->json(['message' => 'Commande non trouvée'], 404);
//        }
//    }

    public function finish($id)
    {
        try {
            // Retrieve the order with the associated client and burger
            $commande = Commande::with('client', 'burger')->find($id);

            // Check if the order exists
            if (!$commande) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            // Check if the client associated with the order exists and has an email
            if (!$commande->client || !$commande->client->email) {
                return response()->json(['error' => 'Client or client email not found'], 404);
            }

            // Update the order status to 'completed'
            $commande->etat = 'Validée';
            $commande->save();

            // Generate the PDF
            $pdf = PDF::loadView('emails.commande', ['order' => $commande]);

            // Send the email with the PDF attachment
            Mail::send([], [], function ($message) use ($commande, $pdf) {
                $message->to($commande->client->email)
                    ->subject('Votre commande est terminée')
                    ->attachData($pdf->output(), 'commande.pdf', [
                        'mime' => 'application/pdf',
                    ]);
            });

            // Return a successful response with no content
            return response()->json(null, 204);

        } catch (\Exception $e) {
            // Log the error message for debugging
            Log::error('Error in finish method: ' . $e->getMessage());

            // Return a JSON response with error details
            return response()->json(['error' => 'Failed to process the request: ' . $e->getMessage()], 500);
        }
    }
}
