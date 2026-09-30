<?php
$billGenerated = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $consumerName = $_POST["consumer_name"];
    $consumerNumber = $_POST["consumer_number"];
    $previousReading = (float)$_POST["previous_reading"];
    $currentReading = (float)$_POST["current_reading"];

    $units = $currentReading - $previousReading;

    if ($units < 0) {
        $error = "Current reading must be greater than or equal to previous reading.";
    } else {

        // Tariff calculation
        if ($units <= 100) {
            $charge = $units * 1.50;
        } 
        elseif ($units <= 200) {
            $charge = (100 * 1.50) + (($units - 100) * 2.50);
        } 
        elseif ($units <= 500) {
            $charge = (100 * 1.50) + 
                      (100 * 2.50) + 
                      (($units - 200) * 4.00);
        } 
        else {
            $charge = (100 * 1.50) + 
                      (100 * 2.50) + 
                      (300 * 4.00) + 
                      (($units - 500) * 6.00);
        }

        $billGenerated = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Electricity Billing System</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 30px;
        }

        .container {
            width: 500px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px #aaa;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
            border: 1px solid #aaa;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #0056b3;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 15px;
        }

        .bill {
            margin-top: 30px;
            border: 2px solid #333;
            padding: 20px;
        }

        .bill h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .bill p {
            font-size: 16px;
            margin: 10px 0;
        }

        .amount {
            font-size: 22px;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }

        .print-btn {
            background: #28a745;
        }

        .print-btn:hover {
            background: #218838;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .container {
                width: 100%;
                box-shadow: none;
            }

            form,
            .print-btn {
                display: none;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Electricity Billing System</h1>

    <form method="POST">

        <label>Consumer Name</label>
        <input type="text" name="consumer_name" required>

        <label>Consumer Number</label>
        <input type="text" name="consumer_number" required>

        <label>Previous Meter Reading</label>
        <input type="number" name="previous_reading"
               min="0" step="0.01" required>

        <label>Current Meter Reading</label>
        <input type="number" name="current_reading"
               min="0" step="0.01" required>

        <button type="submit">Generate Bill</button>

    </form>

    <?php if (isset($error)) { ?>

        <div class="error">
            <?php echo $error; ?>
        </div>

    <?php } ?>

    <?php if ($billGenerated) { ?>

        <div class="bill">

            <h2>Electricity Bill</h2>

            <hr>

            <p>
                <strong>Consumer Name:</strong>
                <?php echo htmlspecialchars($consumerName); ?>
            </p>

            <p>
                <strong>Consumer Number:</strong>
                <?php echo htmlspecialchars($consumerNumber); ?>
            </p>

            <p>
                <strong>Previous Reading:</strong>
                <?php echo $previousReading; ?> Units
            </p>

            <p>
                <strong>Current Reading:</strong>
                <?php echo $currentReading; ?> Units
            </p>

            <p>
                <strong>Units Consumed:</strong>
                <?php echo $units; ?> Units
            </p>

            <hr>

            <div class="amount">
                Total Electricity Charge:
                ₹<?php echo number_format($charge, 2); ?>
            </div>

        </div>

        <button class="print-btn" onclick="window.print()">
            Print Bill
        </button>

    <?php } ?>

</div>

</body>
</html>