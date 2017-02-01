<?php
class TimeWarning extends Controller3
{
    public function indexAction()
    {
            // $query = "SELECT password FROM emp_acc WHERE password='admin'";
            date_default_timezone_set('Asia/Manila');
            $current_date = date('Y-m-d');
            $current_time = date('H:i:s');
            $current_whole_time = date('Y-m-d H:i:s');

            //get time in, time out, and emp_acc id
            $query = "SELECT time_in, time_out, emp_acc_acc_id as ID FROM emp_time WHERE DATE(date) = '$current_date'";
            $sql = $pdo->prepare($query);
            $sql->execute();
            $dataTime = $sql->fetchAll(PDO::FETCH_ASSOC);

            for ($ct = 0; $ct < sizeof($dataTime); $ct++) {
                if($dataTime[$ct]['time_out'] == NULL){
                    $timeID = $dataTime[$ct]['ID']; //get the foreign key of all null

                    $query = "SELECT id FROM emp_profile WHERE emp_acc_acc_id='$timeID'";
                    $sql = $pdo->prepare($query);
                    $sql->execute();
                    $dataProfId = $sql->fetchAll(PDO::FETCH_ASSOC);
                    $profID = $dataProfId[0]['id'];

                    //get email
                    $query = "SELECT contact FROM emp_contact WHERE emp_profile_id='$profID' AND list_cont_types_id=1";
                    $sql = $pdo->prepare($query);
                    $sql->execute();
                    $dataContact = $sql->fetchAll(PDO::FETCH_ASSOC);
                    $conEmail = $dataContact[0]['contact'];
                    var_dump($dataContact);
                    // $yesterday = date('Y-m-d', strtotime("-1 days"));
                    $consumedTime = 0;

                    for ($count = sizeof($dataTime)-1; $count >= 0 ; $count--)
                    {
                        if($dataTime[$count]['ID'] == $timeID)
                        {
                            if($dataTime[$count]['time_out'] == null)
                            {
                                $consumedTime += strtotime($current_whole_time) - strtotime($dataTime[$count]['time_in']);
                            }else{
                                $consumedTime += strtotime($dataTime[$count]['time_out']) - strtotime($dataTime[$count]['time_in']);
                            }                            
                        }
                        if($consumedTime > 28800){
                            echo $conEmail;
                            $remainTime = 28800 - $consumedTime;
                            echo "<br>Time consumed = ".$consumedTime."<br>";
                            include('swiftmailer.php');
                            break;
                        }
                    }

                    // if($timeConsumed >= 200 && $timeConsumed < 500){
                    //     echo 'working';
                    //     $test = $timeRemain;
                    //     include('swiftmailer.php');
                    // }
                }
            }    

//         if($dataTimeOut[3]['cast(time_out as time)'] == NULL){
// //            include('swiftmailer.php');
//             printf("yes ");
//         }
        // echo $dataTimeIn;

        // if ($data) {
        //     // include('swiftmailer.php');
        // }
        // var_dump($data);

        // $query2 = "UPDATE emp_acc SET password='superadmin' WHERE password='admin'";
        // $sql = $pdo->prepare($query2);
        // $sql->execute();
    }
}
?>