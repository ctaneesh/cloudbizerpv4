<!doctype html>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Print Trans #<?php echo $trans['id'] ?></title>

    <style>

        /* @page {
            sheet-size: 220mm 110mm;
        } */

        h1.bigsection {
            page-break-before: always;
            page: bigger;
        }

        table td {
            padding: 8pt;
        }


    </style>

</head>
<body style="font-family: Helvetica;" dir="<?= LTR ?>">

<h5><?php echo $this->lang->line('Transaction Details') . ' ID : ' . prefix(5) . $trans['id'] ?></h5>

<table>
    <?php echo '<tr><td>' . $this->lang->line('Date') . ' : ' . dateformat($trans['date']) . '</td><td>Transaction ID : ' . prefix(5) . $trans['id'] . '</td><td> ' . $this->lang->line('Category') . ' : ' . $trans['cat'] . '</td></tr>'; ?>
</table>

<hr>
<table>
    <tr>
        <td>
            <?php $loc = location($trans['loc']);
            echo '<strong>' . $loc['cname'] . '</strong><br>' .
                $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br> ' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br>  ' . $this->lang->line('Email') . ': ' . $loc['email'];
            ?>

        </td>
        <td> <?php echo '<strong>' . $trans['payer'] . '</strong><br>' .
                $cdata['address'] . '<br>' . $cdata['city'] . '<br>' . $this->lang->line('Phone') . ': ' . $cdata['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $cdata['email']; ?></td>
        
    </tr>
    <tr>
    <td> <?php echo '<strong><u>'.$this->lang->line('Debit').' / '.$this->lang->line('Credit').'</u></strong><p><br><strong>' . $this->lang->line('Debit') . ' : </strong>' . number_format($trans['debit'], 2) . ' </p><p><strong>' . $this->lang->line('Credit') . ' : </strong>' . number_format($trans['credit'], 2) . ' </strong></p><p><strong>' . $this->lang->line('Type') . ' : </strong>' . $trans['type'] . '</p>'; ?></td>
    </tr>
</table>
<?php echo '<p>' . $this->lang->line('Note') . ' : ' . $trans['note'] . '</p>'; ?>
</body>