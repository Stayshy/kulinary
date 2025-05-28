<?php
require 'includes/db.php';
require 'vendor/autoload.php'; // Убедитесь, что установлен TCPDF или используйте mPDF

use TCPDF;

$recipe_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT r.*, c.name AS category_name FROM recipes r JOIN categories c ON r.category_id = c.id WHERE r.id = ?");
$stmt->execute([$recipe_id]);
$recipe = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT i.name, ri.quantity FROM recipe_ingredients ri JOIN ingredients i ON ri.ingredient_id = i.id WHERE ri.recipe_id = ?");
$stmt->execute([$recipe_id]);
$ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('dejavusans', '', 12);

$html = "
<h1>Технико-технологическая карта</h1>
<h2>" . htmlspecialchars($recipe['title']) . "</h2>
<p><strong>Категория:</strong> " . htmlspecialchars($recipe['category_name']) . "</p>
<p><strong>Время приготовления:</strong> " . $recipe['prep_time'] . " мин</p>
<p><strong>Сложность:</strong> " . $recipe['difficulty'] . "</p>
<h3>Ингредиенты:</h3>
<ul>";
foreach ($ingredients as $ingredient) {
    $html .= "<li>" . htmlspecialchars($ingredient['name']) . ": " . htmlspecialchars($ingredient['quantity']) . "</li>";
}
$html .= "</ul>
<h3>Инструкции:</h3>
<p>" . nl2br(htmlspecialchars($recipe['instructions'])) . "</p>";

$pdf->writeHTML($html);
$pdf->Output('ttk_' . $recipe_id . '.pdf', 'D');
?>