<table>
    <tr>
        <td style="text-align: center;vertical-align: middle;" width="30%">
           <h3><?php $loc = location($invoice['loc']); echo $loc['cname']; ?></h3>
        </td>
        <td class="myw1" style="font-size: 15px;" width="40%">
            <?php 
            echo  $loc['address'] . '<br>' . $loc['city'] . ', ';
            if($loc['region'])
            {
                echo $loc['region'] . ', ';
            } 
            echo $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
                ?>
        </td>
        <td class="myco1" style="text-align: right" width="30%">
            <img src="<?php $loc = location($invoice['loc']);
            echo FCPATH . 'userfiles/company/' . $loc['logo'] ?>"
                 class="top_logo">
        </td>
        
    </tr>
</table>
<br>