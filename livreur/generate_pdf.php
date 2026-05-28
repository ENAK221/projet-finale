<?php
require('fpdf/fpdf.php');
include '../components/connect.php';

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    
    // Récupérer les détails de la commande
    $select_order = $conn->prepare("SELECT * FROM `orders` WHERE order_id = ?");
    $select_order->execute([$order_id]);
    $order = $select_order->fetch(PDO::FETCH_ASSOC);

    // Vérifiez si les données sont disponibles
    if (!$order) {
        die('Commande introuvable.');
    }

    // Fonction pour formater la date
    function formatDate($dateStr) {
        $date = new DateTime($dateStr);
        $formattedDate = $date->format('d F Y');
        $months = [
            'January' => 'Janvier', 'February' => 'Février', 'March' => 'Mars', 'April' => 'Avril', 'May' => 'Mai', 'June' => 'Juin',
            'July' => 'Juillet', 'August' => 'Août', 'September' => 'Septembre', 'October' => 'Octobre', 'November' => 'Novembre', 'December' => 'Décembre'
        ];
        $formattedDate = str_replace(array_keys($months), array_values($months), $formattedDate);
        return "Le " . $formattedDate;
    }

    class PDF extends FPDF
    {
        // En-tête
        function Header()
        {
            $this->Image('../images/logo1.png', 10, 6, 30); // Ajoutez le logo de l'entreprise
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'ToolBiTrading_Sarl'), 0, 1, 'R'); // Conversion en windows-1252 pour FPDF
            $this->SetFont('Arial', '', 10);
            $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Email: toolbitradingsarl@outlook.com '), 0, 1, 'R');
            $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Téléphone: +221 76 740 92 95/ 76 991 41 81/ 77 110 60 76'), 0, 1, 'R');
            $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Adresse: Dieupeul-Derklé '), 0, 1, 'R');
            $this->Ln(20); // Ajoute une ligne vide après l'en-tête
        }

        // Pied de page
        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        }
    }

    // Création du PDF
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);

    // Détails de la commande
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Facture'), 0, 1, 'C');
    $pdf->Ln(10);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Informations de la commande:'), 0, 1);
    $pdf->SetFont('Arial', '', 12);
   
    $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Client: ' . $order['name'] . ' ' . $order['surname']), 0, 1);
    $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Adresse: ' . $order['address']), 0, 1);
    $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Date de livraison: ' . formatDate($order['delivery_date'])), 0, 1); // Utilisation de la fonction de formatage de date
    $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Numéro: ' . $order['phone']), 0, 1);
    $pdf->Ln(10);

    $pdf->SetFillColor(51, 122, 183); // Bleu pour la couleur de fond
    $pdf->SetTextColor(255, 255, 255); // Blanc pour la couleur du texte
    $pdf->SetFont('Arial', 'B', 12);
    
    $pdf->Cell(50, 10, iconv('UTF-8', 'windows-1252', 'Description'), 1, 0, 'C', true);
    $pdf->Cell(90, 10, iconv('UTF-8', 'windows-1252', 'Quantité'), 1, 0, 'C', true);
    $pdf->Cell(40, 10, iconv('UTF-8', 'windows-1252', 'Total'), 1, 1, 'C', true);
    
    // Réinitialiser les couleurs par défaut après l'utilisation
    $pdf->SetFillColor(255, 255, 255); // Fond blanc par défaut
    $pdf->SetTextColor(0, 0, 0); // Texte noir par défaut
    
    // Exemple de produit
    $pdf->SetFont('Arial', '', 12);
    
    // Produit
    $pdf->Cell(50, 10, iconv('UTF-8', 'windows-1252', 'Vos Produits'), 1, 0);
    $pdf->Cell(90, 10, iconv('UTF-8', 'windows-1252', $order['total_products']), 1, 0);
    $pdf->Cell(40, 10, iconv('UTF-8', 'windows-1252', $order['total_price'] . ' Fcfa'), 1, 1);
    
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Total TTC: ' . $order['total_price'] . ' Fcfa'), 0, 1, 'R');
    $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Signature Client :'), 0, 0, 'L');

    $pdf->Output();
}
?>
