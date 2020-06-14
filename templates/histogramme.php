<?php
   require_once "../Artichow/BarPlot.class.php";

   $graph = new Graph(400, 400);
   $graph->setAntiAliasing(TRUE);
   
   $values = array(19, 42, 15, -25, 3, 10, 1);
   $plot = new BarPlot($values);
   $plot->setBarColor(
      new Color(250, 230, 180)
   );
   $plot->setSpace(5, 5, NULL, NULL);
	   $days = array(
      'Lundi',
      'Mardi',
      'Mercredi',
      'Jeudi',
      'Vendredi',
      'Samedi',
      'Dimanche'
   );
   $plot->xAxis->setLabelText($days);
   $plot->barShadow->setSize(3);
   $plot->barShadow->setPosition(Shadow::RIGHT_TOP);
   $plot->barShadow->setColor(new Color(180, 180, 180, 10));
   $plot->barShadow->smooth(TRUE);
   
   $graph->add($plot);
   $graph->draw();
?>