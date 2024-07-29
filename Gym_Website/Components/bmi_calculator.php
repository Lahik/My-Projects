<?php 
function calculate_BMI($height, $weight) {
    if ($height > 0 && $weight > 0) {
        $bmi = $weight / (($height / 100) * ($height / 100));
        return $bmi;
    } else {
        return false;
    }
}

function get_BMI_message($bmi) {
    if ($bmi < 18.5) {
        return "Underweight";
    } elseif ($bmi >= 18.5 && $bmi < 25) {
        return "Normal Weight";
    } elseif ($bmi >= 25 && $bmi < 30) {
        return "Overweight";
    } else {
        return "Obese";
    }
}

function get_bmi_class($bmi) {
    switch($bmi) {
        case 'Underweight':
            return 'underweight';
            break;
        case 'Normal Weight':
            return 'normal';
            break;
        case 'Overweight':
            return 'overweight';
            break;
        case 'Obese':
            return 'obese';
            break;
    }
}

$bmi_message = "";
if(isset($_POST['calculate-bmi'])) {
    $height = isset($_POST['height']) ? $_POST['height'] : 0;
    $weight = isset($_POST['weight']) ? $_POST['weight'] : 0;

    $bmi = round(calculate_BMI($height, $weight), 1);

    if($bmi) {
        $bmi_message = get_BMI_message($bmi);
        $_SESSION['bmi'] = array(
            'bmi_score' => $bmi,
            'message' => $bmi_message,
            'class' => get_bmi_class($bmi_message)
        );
        header('location: #bmi');
    }
}
?>