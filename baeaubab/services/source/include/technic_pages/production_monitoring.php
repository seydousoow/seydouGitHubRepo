<?php 
//set the date
date_default_timezone_set("Africa/Dakar");
$date = (isset($_GET['date']) ? filter_var($_GET['date'], FILTER_SANITIZE_NUMBER_INT) : date('Y-m-d'));

//add the page for alert
require_once("source/alert/production_monitoring_alert.php");
require_once("source/model/init.php");

$bd = connect();
$currentBottleStock = $currentPreformStock = [];

//get the stock of preform
$sql = "select stock05, stock19 from production_preform_stock order by date desc limit 1";
$req = $bd->prepare($sql);
$req->execute(array());
while ($data = $req->fetch(PDO::FETCH_ASSOC))
    $currentPreformStock = [$data['stock05'], $data['stock19']];

//get the stock of bottle
$sql = "select stock05, stock19 from production_bottle_stock order by date desc limit 1";
$req = $bd->prepare($sql);
$req->execute(array());
while ($data = $req->fetch(PDO::FETCH_ASSOC))
    $currentBottleStock = [$data['stock05'], $data['stock19']];


//check if there is a record for today
$sql = "select * from production_monitoring where date=?";
$req = $bd->prepare($sql);
$req->execute(array($date));
if ($req->rowCount() > 0) $hasRecord = true;
else $hasRecord = false;
while ($data = $req->fetch(PDO::FETCH_ASSOC))
    $currentData = [$data['conducteur05'], $data['conducteur19'], $data['starting_time05'], $data['starting_time19'], $data['ending_time05'], $data['ending_time19'], $data['production05'], $data['production19'], $data['delivery05'], $data['delivery19'], $data['rebus05'], $data['rebus19'], $data['visa05'], $data['visa19'], $data['responsable05'], $data['responsable19']];

?>

<div id="selectDateContainer" style="margin-bottom: 7px;">
    <p>Modifier la date</p>
    <input type="text" name="dateSelector" id="dateSelector">
    <input type="button" class="btn btn-primary" id="showBtn" value="Afficher">
</div>

<script src="source/include/technic_pages/js/add_production_monitoring.js"></script>
<script>
    const CurrentStockPreform05 = <?php echo json_encode($currentPreformStock[0]) ?>;
    const CurrentStockPreform19 = <?php echo json_encode($currentPreformStock[1]) ?>;
    const CurrentStockBottle05 = <?php echo json_encode($currentBottleStock[0]) ?>;
    const CurrentStockBottle19 = <?php echo json_encode($currentBottleStock[1]) ?>;
    const HasRecord = <?php echo json_encode($hasRecord); ?>;
    $(document).ready(function(){
        document.getElementById("dateSelector").value = "Production du " + new Date(<?php echo json_encode($date); ?>).frenchDate();
    });
</script>

<div class="tg-wrap">
    <form action="source/model/technic/M_add_production_monitoring.php" method="POST">
        <table id="tg-WnK7G" class="tg table" style="table-layout: fixed; width: 750px">
            <colgroup>
                <col style="width: 350px">
                <col style="width: 200px">
                <col style="width: 200px">
            </colgroup>
            <tr>
                <td class="tg-unkg" colspan="3"><span style="font-weight:bold">FEUILLE
                        DE SUIVI DE PRODUCTION</span></td>
            </tr>
            <tr>
                <td class="tg-dc05">Date :
                    <script>document.write(new Date(<?php echo json_encode($date); ?>).frenchDate());</script>
                    <input type="hidden" name="currentDate" id="currentDate"
                        value="<?php echo $date; ?>">
                    <input type="hidden" name="form_state" value="<?php echo ($hasRecord) ? 1 : 0; ?>">
                </td>
                <td class="tg-0big">Bouteille 0,5L</td>
                <td class="tg-ugu0">Bouteille 19L</td>
            </tr>
            <tr>
                <td class="tg-dc05">Stock de Pre&#769;forme:</td>
                <td class="tg-fm9z" style="background-color:darkgray !important">
                    <input style="background-color:darkgray !important;border:none;color:black"
                        type="text" id="prevStockPreform05">
                </td>
                <td class="tg-73ax" style="background-color:darkgray !important">
                    <input style="background-color:darkgray !important;border:none;color:black"
                        type="text" id="prevStockPreform19">
                </td>
            </tr>
            <tr>
                <td class="tg-dc05">Stock de Bouteilles:</td>
                <td class="tg-fm9z" style="background-color:darkgray !important">
                    <input style="background-color:darkgray !important;border:none;color:black"
                        type="text" id="prevStockBottle05">
                </td>
                <td class="tg-73ax" style="background-color:darkgray !important">
                    <input style="background-color:darkgray !important;border:none;color:black"
                        type="text" id="prevStockBottle19">
                </td>
            </tr>
            <tr>
                <td class="tg-dc05">Conducteurs:</td>
                <td class="tg-fm9z"><input type="text" class="technicalProduction_input"
                        name="conduc05" id="conduc05" value="<?php echo ($hasRecord) ? $currentData[0] : ""; ?>"></td>
                <td class="tg-73ax"><input type="text" class="technicalProduction_input"
                        name="conduc19" id="conduc19" value="<?php echo ($hasRecord) ? $currentData[1] : ""; ?>"></td>
            </tr>
            <tr>
                <td class="tg-dc05">Heure de Début</td>
                <td class="tg-fm9z"><input type="time" class="technicalProduction_input"
                        name="start05" id="start05" value="<?php echo ($hasRecord) ? $currentData[2] : ""; ?>"
                        autocomplete="off"></td>
                <td class="tg-73ax"><input type="time" class="technicalProduction_input"
                        name="start19" id="start19" value="<?php echo ($hasRecord) ? $currentData[3] : ""; ?>"
                        autocomplete="off"></td>
            </tr>
            <tr>
                <td class="tg-dc05">Heure de Fin</td>
                <td class="tg-fm9z"><input type="time" class="technicalProduction_input"
                        name="end05" id="end05" value="<?php echo ($hasRecord) ? $currentData[4] : ""; ?>"></td>
                <td class="tg-73ax"><input type="time" class="technicalProduction_input"
                        name="end19" id="end19" value="<?php echo ($hasRecord) ? $currentData[5] : ""; ?>"></td>
            </tr>
            <tr>
                <td class="tg-dc05">Quantité Produite</td>
                <td class="tg-fm9z"><input type="text" class="technicalProduction_input"
                        name="produc05" id="produc05" value="<?php echo ($hasRecord) ? $currentData[6] : ""; ?>"
                        autocomplete="off"></td>
                <td class="tg-73ax"><input type="text" class="technicalProduction_input"
                        name="produc19" id="produc19" value="<?php echo ($hasRecord) ? $currentData[7] : ""; ?>"
                        autocomplete="off"></td>
            </tr>
            <tr>
                <td class="tg-dc05">Total de bouteilles disponibles</td>
                <td class="tg-fm9z" style="background-color: darkgray;"><input
                        type="text" class="technicalProduction_input" name="totalBottle05"
                        id="totalBottle05" value="" disabled></td>
                <td class="tg-73ax" style="background-color: darkgray;"><input
                        type="text" class="technicalProduction_input" name="totalBottle19"
                        id="totalBottle19" value="" disabled></td>
            </tr>
            <tr>
                <td class="tg-dc05">Quantité Livrée</td>
                <td class="tg-fm9z"><input type="text" class="technicalProduction_input"
                        name="deliv05" id="deliv05" value="<?php echo ($hasRecord) ? $currentData[8] : ""; ?>"
                        autocomplete="off"></td>
                <td class="tg-73ax"><input type="text" class="technicalProduction_input"
                        name="deliv19" id="deliv19" value="<?php echo ($hasRecord) ? $currentData[9] : ""; ?>"
                        autocomplete="off"></td>
            </tr>
            <tr>
                <td class="tg-dc05">Nombre de Rebus</td>
                <td class="tg-fm9z"><input type="text" class="technicalProduction_input"
                        name="rebus05" id="rebus05" value="<?php echo ($hasRecord) ? $currentData[10] : ""; ?>"
                        autocomplete="off"></td>
                <td class="tg-73ax"><input type="text" class="technicalProduction_input"
                        name="rebus19" id="rebus19" value="<?php echo ($hasRecord) ? $currentData[11] : ""; ?>"
                        autocomplete="off"></td>
            </tr>
            <tr>
                <td class="tg-dc05">Nouveau stock de Pre&#769;forme</td>
                <td class="tg-fm9z" style="background-color: darkgray;">
                    <input type="text" class="technicalProduction_input" name="newPreformStock05"
                        id="newPreformStock05" readonly></td>
                <td class="tg-73ax" style="background-color: darkgray;">
                    <input type="text" class="technicalProduction_input" name="newPreformStock19"
                        id="newPreformStock19" readonly></td>
            </tr>
            <tr>
                <td class="tg-dc05">Nouveau stock de Bouteilles</td>
                <td class="tg-fm9z" style="background-color: darkgray;"><input
                        type="text" class="technicalProduction_input" name="newBottleStock05"
                        id="newBottleStock05"></td>
                <td class="tg-73ax" style="background-color: darkgray;"><input
                        type="text" class="technicalProduction_input" name="newBottleStock19"
                        id="newBottleStock19" readonly></td>
            </tr>
            <tr>
                <td class="tg-mv9o">Visa Chef de Ligne</td>
                <td class="tg-jtts"><input type="text" class="technicalProduction_input"
                        name="visa05" id="visa05" value="<?php echo ($hasRecord) ? $currentData[12] : ""; ?>"
                        autocomplete="off"></td>
                <td class="tg-ayio"><input type="text" class="technicalProduction_input"
                        name="visa19" id="visa19" value="<?php echo ($hasRecord) ? $currentData[13] : ""; ?>"
                        autocomplete="off"></td>
            </tr>
            <tr>
                <td class="tg-mv9o">Responsable<br></td>
                <td class="tg-jtts"><input type="text" class="technicalProduction_input"
                        name="resp05" id="resp05" value="<?php echo ($hasRecord) ? $currentData[14] : ""; ?>"
                        autocomplete="off"></td>
                <td class="tg-ayio"><input type="text" class="technicalProduction_input"
                        name="resp19" id="resp19" value="<?php echo ($hasRecord) ? $currentData[15] : ""; ?>"
                        autocomplete="off"></td>
            </tr>
        </table>
        <!-- <button class="btn btn-success" onclick="printData();">Imprimer</button> -->
        <input type="button" name="submitProductionMonitoring" class="submitBtnProduction"
            onclick="formValidation();" value="<?php echo ($hasRecord) ? "
            Mettre a jour" : "Enregistrer"; ?>">
    </form>
</div>