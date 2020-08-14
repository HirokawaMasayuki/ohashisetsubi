<?php
$this->layout = '';
?>
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#E6FFFF">
  <tr style="background-color: #E6FFFF">
    <td>
        <table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
          <tr style="border-style: none; background-color: #E6FFFF">
            <td bgcolor="#E6FFFF">
            </body>
            </td>
          </tr>
        </table>
        <br><br>
        <table style="margin-bottom:0px" width="1000" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
                  <tr style="border-style: none; background-color: #E6FFFF">
                    <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/uriagesyori.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Accounts','action'=>'uriagemenu')));?></td>
                    <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/shinki.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Shinkies','action'=>'menu')));?></td>
                    <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/kokyaku.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Accounts','action'=>'form')));?></td>
                  </tr>
        </table>
            <br><br>
    </td>
  </tr>
</table>
