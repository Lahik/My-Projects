<?php 
function validateCardNumber($cardNumber) {
    $cardNumber = strrev(preg_replace('/[^\d]/','',$cardNumber));
    $sum = 0;
    for ($i = 0, $j = strlen($cardNumber); $i < $j; $i++) {
        $digit = (int)$cardNumber[$i];
        if ($i % 2 == 0) {
            $digit *= 2;
            if ($digit > 9) {
                $digit -= 9;
            }
        }
        $sum += $digit;
    }
    return $sum % 10 == 0;
}

function validateExpiryDate($expiryDate) {
    $currentYear = date('y');
    $currentMonth = date('m');
    echo $currentYear.'  '.$currentMonth;
    list($month, $year) = explode('/', $expiryDate);
    return ($year > $currentYear || ($year == $currentYear && $month >= $currentMonth));
}

function validateTypeAndTotal($plan, $total, $conn) {
    $verify_membership = $conn->prepare("SELECT * FROM membership_plan WHERE plan = ? AND amount = ?");
    $verify_membership->execute([$plan, $total]);
    return $verify_membership->rowCount() > 0;
}
?>