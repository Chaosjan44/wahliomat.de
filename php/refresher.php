<?php
chdir ($_SERVER['DOCUMENT_ROOT']);
require_once("php/functions.php");
$poll_user = check_poll_user();
if ($poll_user == false) {
    print('<h1 class="text-center text-danger display-5">Seite neu laden!</h1>');
    print('<div style="display: none">refresh_now_true</div>');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM questions where poll_id  = ? and current = 1');
$stmt->bindValue(1, $poll_user["poll_id"], PDO::PARAM_INT);
$stmt->execute();
if ($stmt->rowCount() < 1) {
    echo('<h3 class="display-6 text-center text-kolping-orange">Warte bis die nächste Frage freigegeben wird.</h3>');
    print('<div style="display: none">refresh_now_true</div>');
    exit;
} else {
    if ($poll_user['answered_current'] == 1) {
        echo('<h3 class="display-6 text-center text-success">Du hast deine Stimme abgegeben</h3>');
        print('<div style="display: none">refresh_now_true</div>');
        exit;
    }
    if ($poll_user['refresh'] == 1) {
        $question = $stmt->fetch();

        $stmt = $pdo->prepare('SELECT * FROM options where question_id  = ?');
        $stmt->bindValue(1, $question["question_id"], PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            error('Fehler!');
        } 
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare('SELECT * FROM polls where poll_id  = ?');
        $stmt->bindValue(1, $poll_user["poll_id"], PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() != 1) {
            error_log($stmt->rowCount());
            exit;
        }
        $poll = $stmt->fetch();

        $stmt = $pdo->prepare("UPDATE polls_users SET refresh = 0 WHERE poll_user_id = ?");
        $stmt->bindValue(1, $poll_user["poll_user_id"]);
        $result4 = $stmt->execute();
        if (!$result4) {
            error('Datenbank Fehler!', pdo_debugStrParams($stmt));
        } 
        print('<div style="display: none">refresh_now_true</div>');

        echo('<form action="/php/givevote.php" method="POST"><div class="card-title row"><h2 class="col-8 text-kolping-orange text-start">' . $question["question"] . '</h2><h class="col-4 ctext text-end"><span id="cur_checked">0</span> / ' . $question["options_amount"] . '</h2></div>');
        echo($poll_user['error_msg']);
        foreach ($options as $option) {
        print('<div class="input-group justify-content-center my-2"><label for="'. $option["option_id"] . '" class="input-group-text">' . $option["option_name"] . '</label><div class="input-group-text"><input type="number" value="' . $option["option_id"] . '" name="option_' . $option["option_id"] . '" style="display: none;" required><input id="' . $option["option_id"] . '" type="checkbox" name="vote_' . $option["option_id"] . '" value="0" class="form-check-input checkbox-kolping mt-0" onchange="updatecur()"></div></div>');
        }
        print('<div class="d-grid gap-2 d-md-flex justify-content-md-center"><button type="submit" name="action" value="submit" class="btn btn-success my-2">Abstimmen</button></div></form>');
    }
}
?>