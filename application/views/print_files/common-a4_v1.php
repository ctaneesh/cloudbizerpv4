<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo $caption ?></title>
    <style>
        body {
            color: #2B2000;
            font-family: 'Helvetica', sans-serif;
        }

        .invoice-box {
            width: 297mm; /* Landscape width */
            height: 210mm; /* Landscape height */
            margin: auto;
            padding: 10mm;
            border: 0;
            font-size: 12pt;
            line-height: 14pt;
            color: #000;
        }

        table {
            width: 100%;
            line-height: 16pt;
            text-align: left;
            border-collapse: collapse;
        }

        table th, table td {
            /* border: 1px solid #ddd; */
            padding: 8px;
            text-align: left;
        }

        table thead tr {
            background:rgb(232, 232, 232);
            color: #FFF;
        }

        .invoice-box table tr.item td {
            border: 1px solid #ddd;
        }

        .invoice-box img {
            max-width: 100%; /* Ensure logo scales correctly */
            height: auto;
        }

        .maintable {
            border-collapse: collapse;
            width: 100%;
        }

        .maintable th, .maintable td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .maintable tr:nth-child(even) {
            background-color: #f5f1f1;
        }

        .top_logo {
            max-height: 100px;
            max-width: 200px;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table>
            <tbody>
                <tr>
                    <td style="text-align: center; vertical-align: middle;" width="40%">
                        <strong><h3><?php echo $companyNanme; ?></h3></strong>
                    </td>
                    <td style="font-size: 14px;" width="40%">
                        <?=$lang['company'] ?>
                    </td>
                    <td style="text-align: right" width="20%"> 
                        <?php $imgUrl = base_url() . "userfiles/company/company-logo.jpg"; ?>
                        <img src="<?=$imgUrl?>" class="top_logo">
                    </td>
                </tr>
            </tbody>
        </table>

        <hr>
        
        <!-- Table Content -->
        <table class="maintable">
            <thead>
                <tr>
                    <?php foreach ($display_fields as $field): ?>
                        <th><?php echo htmlspecialchars($field); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php $serial_number = 1; ?>
                <?php foreach ($output_data as $row): ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $serial_number++; ?></td>
                        <?php foreach ($row as $cell): ?>
                            <td><?php echo htmlspecialchars($cell); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
