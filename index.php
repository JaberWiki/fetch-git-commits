<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="./style.css">
    <title>GitHub Commits</title>
</head>

<body>
    <div id="toast" class="toast btn">Copied!</div>
    <div class="main">
        <form method="post">
            <div class="input-group mb-3 col-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Your Username:</span>
                </div>
                <input type="text" class="form-control" id="username" name="user" required aria-label="User" aria-describedby="basic-addon1" value="<?php echo isset($_POST['user']) ? trim($_POST['user']) : '' ?>">
            </div>

            <div class="input-group mb-3 col-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Repository Name:</span>
                </div>
                <input type="text" class="form-control" id="reponame" name="reponame" required aria-label="Reponame" aria-describedby="basic-addon1" value="<?php echo isset($_POST['reponame']) ? trim($_POST['reponame']) : '' ?>">
            </div>

            <div class="input-group mb-3 col-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Repository Owner:</span>
                </div>
                <input type="text" class="form-control" id="repoowner" name="repoowner" required aria-label="Repoowner" aria-describedby="basic-addon1" value="<?php echo isset($_POST['repoowner']) ? trim($_POST['repoowner']) : '' ?>">
            </div>

            <div class="input-group mb-3 col-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Access Token:</span>
                </div>
                <input type="text" class="form-control" id="accesstoken" name="accesstoken" required aria-label="Accesstoken" aria-describedby="basic-addon1" value="<?php echo isset($_POST['accesstoken']) ? trim($_POST['accesstoken']) : '' ?>">
            </div>

            <div class="input-group mb-3 col-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Branch Name:</span>
                </div>
                <input type="text" class="form-control" id="branchname" name="branchname" required aria-label="Branchname" aria-describedby="basic-addon1" value="<?php echo isset($_POST['branchname']) ? trim($_POST['branchname']) : '' ?>">
            </div>

            <div class="input-group mb-3 col-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Date (DD-MM-YYYY):</span>
                </div>
                <input type="text" class="form-control date" id="date" name="date" required aria-label="Date" aria-describedby="basic-addon1" value="<?php echo isset($_POST['date']) ? trim($_POST['date']) : '' ?>">
            </div>

            <input type="submit" name="submit" class="btn btn-primary ml-3" value="Get Commits">
        </form>

        <div class="text-left" id="commits">
            <?php
            if (isset($_POST['submit'])) {
                // Get the input values from the form
                $reponame = trim($_POST['reponame']);
                $repoowner = trim($_POST['repoowner']);
                $token = trim($_POST['accesstoken']);
                $branchname = trim($_POST['branchname']);
                $user = trim($_POST['user']);
                $date = trim($_POST['date']);
                $timestamp = trim(strtotime($date));
                $utc_date = trim(gmdate('Y-m-d', $timestamp));
                $since_date = trim($utc_date . 'T00:00:00Z');
                $until_date = trim($utc_date . 'T23:59:59Z');

                $url = "https://api.github.com/repos/$repoowner/$reponame/commits?author=$user&sha=$branchname&since=$since_date&until=$until_date";

                // Send the API request using cURL
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Authorization: token ' . $token,
                    'User-Agent: My-App'
                ));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                curl_close($ch);

                // Process the API response
                $data = json_decode($result, true);

                if (empty($data)) {
                    echo "No commits found by user '$user' in branch '$branchname' after date '$date'.";
                } else {
                    $formatted_date = date('jS F, Y', strtotime($date));
                    echo "<pre>Today's ( $formatted_date ) activities are - \n";
                    $index = 0;
                    foreach ($data as $key => $commit) {
                        $message = $commit['commit']['message'];
                        if (strpos($message, 'Merge branch') === 0) {
                            continue; //skip merge commits
                        }
                        $link = $commit['html_url'];
                        $index++;
                        echo "$index) $message \n- $link\n";
                    }
                    echo "</pre>";
                }
            }
            ?>
        </div>
        <button onclick="myFunction()">Copy text</button>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js" integrity="sha512-LsnSViqQyaXpD4mBBdRYeP6sRwJiJveh2ZIbW41EBrNmKxgr/LFZIiWT6yr+nycvhvauz8c2nYMhrP80YhG7Cw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="./script.js"></script>
</body>

</html>