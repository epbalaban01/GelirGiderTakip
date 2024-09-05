<?php include('db.php'); ?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelir Tablosu</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .dataTables_wrapper .dt-buttons {
            margin-bottom: 10px;
            text-align: left;
            display: inline-block;
        }


        .top-bar {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }


        .dataTables_wrapper .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        @media screen and (max-width: 600px) {
            .month-dropdown select {
                width: 100%;
            }

            .dataTables_wrapper .top-bar {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body class="income-table-page">
    <div class="container">
        <h1 class="text-center my-4">Gelir Tablosu</h1>

        <?php
        $month = isset($_GET['month']) ? $_GET['month'] : date('F');

        $stmt = $pdo->prepare("SELECT SUM(amount) as total_amount FROM income WHERE MONTHNAME(date) = :month");
        $stmt->execute(['month' => $month]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_amount = $row['total_amount'];
        ?>

        <h2 class="text-center my-4">Toplam Tutar (<?php echo $month; ?>): <?php echo number_format($total_amount, 2, ',', '.') . " ₺"; ?></h2>

        <div class="table-responsive">
            <div class="top-bar d-flex justify-content-between">
                <div class="dt-buttons"></div>
                <div class="month-dropdown">
                    <form method="GET" action="">
                        <select name="month" onchange="this.form.submit()" class="form-control">
                            <option value="January" <?php if ($month == 'January') echo 'selected'; ?>>Ocak</option>
                            <option value="February" <?php if ($month == 'February') echo 'selected'; ?>>Şubat</option>
                            <option value="March" <?php if ($month == 'March') echo 'selected'; ?>>Mart</option>
                            <option value="April" <?php if ($month == 'April') echo 'selected'; ?>>Nisan</option>
                            <option value="May" <?php if ($month == 'May') echo 'selected'; ?>>Mayıs</option>
                            <option value="June" <?php if ($month == 'June') echo 'selected'; ?>>Haziran</option>
                            <option value="July" <?php if ($month == 'July') echo 'selected'; ?>>Temmuz</option>
                            <option value="August" <?php if ($month == 'August') echo 'selected'; ?>>Ağustos</option>
                            <option value="September" <?php if ($month == 'September') echo 'selected'; ?>>Eylül</option>
                            <option value="October" <?php if ($month == 'October') echo 'selected'; ?>>Ekim</option>
                            <option value="November" <?php if ($month == 'November') echo 'selected'; ?>>Kasım</option>
                            <option value="December" <?php if ($month == 'December') echo 'selected'; ?>>Aralık</option>
                        </select>
                    </form>
                </div>
            </div>


            <table id="incomeTable" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tarih</th>
                        <th>Kategori</th>
                        <th>Açıklama</th>
                        <th>Tutar</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM income WHERE MONTHNAME(date) = :month ORDER BY date DESC");
                    $stmt->execute(['month' => $month]);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $formattedDate = date("d.m.Y", strtotime($row['date']));
                        $formattedAmount = number_format($row['amount'], 2, ',', '.');
                        echo "<tr>
                                <td style='text-align: center;'>{$row['id']}</td>
                                <td style='text-align: center;'>{$formattedDate}</td>
                                <td style='text-align: center;'>{$row['category']}</td>
                                <td style='text-align: left;'>{$row['description']}</td>
                                <td style='text-align: center;'>{$formattedAmount} ₺</td>
                                <td style='text-align: center;'>
                                    <a href='edit.php?id={$row['id']}' class='btn btn-sm btn-warning'>Düzenle</a>
                                    <a href='delete.php?id={$row['id']}' class='btn btn-sm btn-danger'>Sil</a>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <br>
        <a href="dashboard.php" class="btn btn-primary btn-block">Dashboard'a Geri Dön</a>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#incomeTable').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "iDisplayLength": -1,
                "language": {
                    'search': 'Ara',
                    'sInfo': '_TOTAL_ tablodan _START_ ile _END_ tablo gösteriliyor',
                    "paginate": {
                        'next': 'İleri',
                        'previous': 'Geri'
                    }
                },
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Excel',
                        className: 'btn btn-success'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        className: 'btn btn-danger'
                    },
                    {
                        extend: 'print',
                        text: 'Yazdır',
                        className: 'btn btn-info'
                    },
                    {
                        extend: 'pageLength',
                        text: 'Kayıt Sayısı',
                        className: 'btn btn-danger'
                    }
                ],
                "lengthMenu": [
                    [10, 20, 50, -1],
                    [10, 20, 50, "Hepsi"],
                ],
                pageLength: 10,
                lengthChange: false,
                columnDefs: [{
                    orderable: false,
                    targets: 5
                }],
                scrollX: true, // Yatay kaydırmayı etkinleştirir
                scrollCollapse: true // Tablo içerikleri bittiğinde kaydırma çubuğu yok olur
            });
        });
    </script>
</body>

</html>