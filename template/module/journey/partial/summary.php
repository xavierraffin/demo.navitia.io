<?php use Nv2\Model\Entity\Journey\Journey; ?>

<?php if (!isset($journey) || $journey->Type == Journey::TYPE_PUBLIC_TRANSPORT) { ?>
    <?php if ($journeySummary['clockwise'] == 'departure') { ?>
        <h2>Départ le <?php echo date('d/m/Y', $journeySummary['timestamp']); ?> après <?php echo date('H:i', $journeySummary['timestamp']); ?></h2>
    <?php } else if ($journeySummary['clockwise'] == 'arrival') { ?>
        <h2>Arrivée le <?php echo date('d/m/Y', $journeySummary['timestamp']); ?> avant <?php echo date('H:i', $journeySummary['timestamp']); ?></h2>
    <?php } ?>
<?php } ?>

<?php if (isset($journey)) { ?>
    <ul>
        <!--<li>Prix du trajet :<br /><strong> <abbr title="euros">&euro;</abbr></strong></li>-->
        <li>Durée :<br /><strong><?php echo gmdate('H:i', ($journey->Duration)); ?></strong></li>
        <?php if ($journey->NbTransfers == 0) { ?>
            <li><br /><strong>Trajet direct</strong></li>
        <?php } else { ?>
            <li>Correspondances :<br /><strong><?php echo $journey->NbTransfers; ?></strong></li>
        <?php } ?>
    </ul>
<?php } ?>