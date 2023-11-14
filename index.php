<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        body {
            padding: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        

        .input-group {
            margin-bottom: 15px;
        }

        .btn-primary {
            margin-top: 15px;
        }

        .commits {
            margin-top: 20px;
        }

        .commits p {
            margin-bottom: 5px;
        }
    </style>
    <title>GitHub Commits</title>
</head>

<body>
    <div class="main">
        <form method="post">
            <div class="input-group mb-3 col-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Your Username:</span>
                </div>
                <input type="text" class="form-control" id="username" name="user" required aria-label="User" aria-describedby="basic-addon1">
            </div>

            <div class="input-group mb-3 col-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Repository Name:</span>
                </div>
                <input type="text" class="form-control" id="reponame" name="reponame" required aria-label="Reponame" aria-describedby="basic-addon1">
            </div>

            <div class="input-group mb-3 col-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Repository Owner:</span>
                </div>
                <input type="text" class="form-control" id="repoowner" name="repoowner" required aria-label="Repoowner" aria-describedby="basic-addon1">
            </div>

            <div class="input-group mb-3 col-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Access Token:</span>
                </div>
                <input type="text" class="form-control" id="accesstoken" name="accesstoken" required aria-label="Accesstoken" aria-describedby="basic-addon1">
            </div>

            <div class="input-group mb-3 col-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Branch Name:</span>
                </div>
                <input type="text" class="form-control" id="branchname" name="branchname" required aria-label="Branchname" aria-describedby="basic-addon1">
            </div>

            <div class="input-group mb-3 col-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Date (DD-MM-YYYY):</span>
                </div>
                <input type="text" class="form-control date" id="date" name="date" required aria-label="Date" aria-describedby="basic-addon1">
            </div>

            <input type="submit" name="submit" class="btn btn-primary ml-3" value="Get Commits">
        </form>

        <div class="text-left">
            <?php
            if (isset($_POST['submit'])) {
                // Get the input values from the form
                $reponame = $_POST['reponame'];
                $repoowner = $_POST['repoowner'];
                $token = $_POST['accesstoken'];
                $branchname = $_POST['branchname'];
                $user = $_POST['user'];
                $date = $_POST['date'];
                $timestamp = strtotime($date);
                $utc_date = gmdate('Y-m-d', $timestamp);
                $since_date = $utc_date . 'T00:00:00Z';
                $until_date = $utc_date . 'T23:59:59Z';

                $url = "https://api.github.com/repos/$repoowner/$reponame/commits?author=$user&sha=$branchname&since=$since_date&until=$until_date";

                // Set the API endpoint URL

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
                    echo "Today's ( $formatted_date ) activities are -";
                    $index = 0;
                    foreach ($data as $key => $commit) {
                        $message = $commit['commit']['message'];
                        if (strpos($message, 'Merge branch') === 0) {
                            continue; //skip merge commits
                        }
                        $link = $commit['html_url'];
                        $index++;
                        echo "<p>$index) $message <br/>- $link</p>";
                    }
                }
            }
            ?>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js" integrity="sha512-LsnSViqQyaXpD4mBBdRYeP6sRwJiJveh2ZIbW41EBrNmKxgr/LFZIiWT6yr+nycvhvauz8c2nYMhrP80YhG7Cw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $('.date').datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
            todayHighlight: true,
            showOtherMonths: true
        }).datepicker("setDate", new Date());
    </script>
</body>

</html>
