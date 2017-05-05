<?php
/**
 * Created by PhpStorm.
 * User: Propelrr-AJ
 * Date: 09/08/16
 * Time: 12:48 PM
 */

namespace AdminBundle\Controller;


use CoreBundle\Model\EmpAccPeer;
use CoreBundle\Model\EmpProfilePeer;
use CoreBundle\Model\EmpProfileQuery;
use CoreBundle\Model\ListEventsPeer;
use CoreBundle\Model\ListEventsTypePeer;
use CoreBundle\Model\ListRequestTypePeer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Message;
use Symfony\Component\HttpFoundation\JsonResponse;


class EmailController extends Controller
{
    /**
     * Request out of office email
     * @param $req
     * @param $class
     * @param null $reqId
     * @return int
     */
    public function sendTimeInRequest($req, $class, $reqId = null)
    {
        $user = $class->getUser();
        $id = $user->getId();

        //employee profile information
        $data = EmpProfilePeer::getInformation($id);

        $admins = EmpAccPeer::getAdminInfo();
        $empName = $data->getFname() . ' ' . $data->getLname();
        $message = $req->request->get('message');
        $adminemails = array();

        $from = array('no-reply@searchoptmedia.com', 'PROLS');
        $subject = 'PROLS » Request for Access';

        foreach ($admins as $admin){
            $adminemails[] = $admin->getEmail();
        }

        $to = array($adminemails);
        $date = date('F d, Y') . ' at ' . date('h:i a') ;
        $requestDates = array(
            array(
                'start' => $date,
                'end' => $date,
                'reason' => $message
            )
        );

        $emailContent = $class->renderView('AdminBundle:Templates/Email:email-has-table.html.twig', array(
            'data' => $requestDates,
            'title' => 'Request Access',
            'greetings' => 'Hi Admin,',
            'template' => 'request-access',
            'message' => $empName . ' timed in outside the office.',
            'links' => array(
                'View Request' =>  $class->generateUrl('view_request',  array('id' => $reqId), true)
            )
        ));

        $email = self::sendEmail($class, $subject, $from, $to,$emailContent);

        return $email ? 1: 0;
    }

    /**
     * Approve/Decline requests
     * @param $req
     * @param $class
     * @return int
     */
    public function acceptRequestEmail($req, $class)
    {
        $empid = $req->request->get('empId');
        $reqId =   $req->request->get('reqid');
        $user = $class->getUser();
        $id = $user->getId();
        $email = 0;

        $employee = EmpAccPeer::retrieveByPK($empid);
        $note = $req->request->get('comment');
        $status = $req->request->get('status');
        $reason = $req->request->get('reason');
        $changed = $req->request->get('isChanged');

        if($status == 3)
            $status = "Approved";
        else
            $status = "Declined";

        if(! empty($employee)) {
            $empemail = $employee->getEmail();
            $empinfo = EmpProfilePeer::getInformation($employee->getId());
            $empname = $empinfo->getFname() . " " .$empinfo->getLname();

            //admin profile information
            $data = EmpProfilePeer::getInformation($id);
            $name = $data->getFname(). " " .$data->getLname();
            $requestName = $req->request->get('requestname');

            if ($changed == "CHANGED")
                $subject = "PROLS » " . ucwords(strtolower($requestName)) . " " . " Request Changed";
            else
                $subject = "PROLS » " . ucwords(strtolower($requestName)) . " " . " Request " . $status;

            $from = array('no-reply@searchoptmedia.com', 'PROLS');
            $to = array($empemail);

            $requestDates = array(
                array('start' => date('F d, Y', strtotime($req->request->get('datestart'))), 'end' => date('F d, Y', strtotime($req->request->get('dateend'))), 'reason' => $reason)
            );

            $emailContent = $class->renderView('AdminBundle:Templates/Email:email-has-table.html.twig', array(
                'data' => $requestDates,
                'title' => strtolower($requestName)!='work out of office' ? ucwords(strtolower($requestName)) : 'Request Access',
                'greetings' => 'Hi '.$empname.',',
                'template' => 'approve-decline',
                'message' => "<strong>$name</strong> has <strong>".strtolower($status)."</strong> your ".strtolower($requestName).".",
                'links' => array(
                    'View Request' =>  $class->generateUrl('view_request',  array('id' => $reqId), true)
                ),
                'approval_reason' => $note,
            ));

            $email = self::sendEmail($class, $subject, $from, $to, $emailContent);
        }

        return $email ? 1: 0;
    }

    /**
     * Adding new employee - send credentials|Notify admin
     * @param $req
     * @param $class
     * @return int
     */
    public function addEmployeeEmail($req, $class){
        $user = $class->getUser();
        $id = $user->getId();

        $profile = EmpProfilePeer::getInformation($id);
        $empname = $req->request->get('fnameinput') . " " . $req->request->get('lnameinput');
        $employeeemail = $req->request->get('emailinput');
        $empusername = $req->request->get('usernameinput');
        $emppassword = $req->request->get('passwordinput');

        $subject = "PROLS » Your Account Was Successfully Created";
        $from = array('no-reply@searchoptmedia.com', 'PROLS');
        $to = array($employeeemail);

        $emailContent = $class->renderView('AdminBundle:Templates/Email:email-has-table.html.twig', array(
            'title' => 'Login System',
            'greetings' => 'Hi '.$empname.',',
            'username' => $empusername,
            'password' => $emppassword,
            'template' => 'account-create-employee',
            'message' => 'Your account was successfully created. Use the following details to login at <a href="'.$class->generateUrl('login', array(), true).'">login.propelrr.com</a>.',
            'links' => array(
                'Login Now!' => $class->generateUrl('login', array(), true)
            )
        ));

        //-------- email employee
        $email = self::sendEmail($class, $subject, $from, $to, $emailContent);

        //check if doer is an admin
        $admins = EmpAccPeer::getAdminInfo();
        $adminemails = array();

        foreach ($admins as $admin){
            $adminemails[] = $admin->getEmail();
        }

        $to = array($adminemails);
        $currentUserEmail = $class->getUser()->getEmail();

        if(!in_array($currentUserEmail, $adminemails)) {
            $name = $profile->getFname() . ' ' . $profile->getLname();
            $emailContent = $class->renderView('AdminBundle:Templates/Email:email-has-table.html.twig', array(
                'title' => 'Login System',
                'greetings' => 'Hi Admin,',
                'message'  => 'Account for <strong>'.$empname.'</strong> was successfully created by <strong>'.$name.'</strong>.',
                'template' => 'account-create-admin',
                'links' => array(
                    'Manage Employees' => $class->generateUrl('manage_employee', array(), true)
                )
            ));

            //-------- email admin
            $email = self::sendEmail($class, "PROLS » Account Successfully Created", $from, $to, $emailContent);
        }

        return $email ? 1: 0;
    }

    /**
     * Update employee details - notify admin
     * @param $req
     * @param $class
     * @param array $changes
     * @return int
     */
    public function adminEditEmployeeProfileEmail($req, $class, $changes = array()){
        $user = $class->getUser();
        $id = $user->getId();

        $userProfile = EmpProfilePeer::getInformation($id);
        $userFullName = $userProfile->getFname() . " " . $userProfile->getLname();
        $employeeName = $req->request->get('fnameinput') . " " . $req->request->get('lnameinput');

        $from = array('no-reply@searchoptmedia.com', 'PROLS');
        $email = 0;

        $admins = EmpAccPeer::getAdminInfo();
        $subject = "PROLS » Employee Profile Updated";
        $adminEmailList = $this->getAdminEmails($admins);

        if(count($adminEmailList) && !in_array($user->getEmail(), $adminEmailList)) {
            $to = $adminEmailList;

            $emailContent = $class->renderView('AdminBundle:Templates/Email:email-has-table.html.twig', array(
                'title' => 'Profile Update',
                'greetings' => 'Hi Admin,',
                'message' => "<strong>$userFullName</strong> has updated <strong>$employeeName</strong>'s profile.",
                'template' => 'profile-update',
                'data' => $changes
            ));

            $email = self::sendEmail($class, $subject, $from, $to,  $emailContent);
        }

        return $email ? 1: 0;
    }

    /**
     * Notify admin if request has been updated/cancelled by employee
     * @param $req
     * @param $class
     * @param $action
     * @param array $param
     * @return int
     */
    public function notifyRequestEmail($req, $class, $action, $param = array()) {
        $user = $class->getUser();
        $id = $user->getId();

        $empinfo = EmpProfilePeer::getInformation($id);
        $empname = $empinfo->getFname() . " " . $empinfo->getLname();
        $gender  = strtolower($empinfo->getGender());
        $genderPref = ($gender=='male') ? 'his' : ($gender=='female' ? 'her' : 'his/her');
        $category = $req->request->get('category');
        $admins = EmpAccPeer::getAdminInfo();
        $emailCtr = 0;

        $subject = "PROLS » " . ucwords(strtolower($action)) . " " . ucwords(strtolower($category)) . " Request";
        $from = array('no-reply@searchoptmedia.com', 'PROLS');

        $adminEmailList = $this->getAdminEmails($admins);

        $newStartDate = $req->request->get('start_date');
        $newEndDate = $req->request->get('end_date');
        $newReason = $req->request->get('reason');
        $oldData = array();

        $data = array(
            array(
                'start' => date('F d, Y', strtotime($newStartDate)),
                'end' => date('F d, Y', strtotime($newEndDate)),
                'reason' => $newReason
            )
        );

        if($action!='CANCELLED') {
            $oldData = $param ? array(
                array(
                    'start' => $param['startDate'],
                    'end' => $param['endDate']
                )
            ) : null;
        } else {
            $data = array(
                array(
                    'start' => $param['startDate'],
                    'end' => $param['endDate'],
                    'reason' => $param['reason']
                )
            );
        }

        //check if changes on date
        if($oldData) {
            foreach($oldData as $d) {
                if($d['start']==date('F d, Y', strtotime($newStartDate)) && $d['end']==date('F d, Y', strtotime($newEndDate))) {
                    $oldData = null;
                }
            }
        }

        if(count($adminEmailList)) {
            $to = $adminEmailList;
            $message = "<strong>".$empname . "</strong> has ". strtolower($action) ." $genderPref <strong>" . strtolower($category) . "</strong>.";

            $emailContent = $class->renderView('AdminBundle:Templates/Email:email-has-table.html.twig', array(
                'title'     => strtolower($category)!='work out of office' ? ucwords(strtolower($category)) : 'Request Access',
                'greetings' => 'Hi Admin,',
                'template'  => 'update-request',
                'message'   => $message,
                'links'     => ($action!='CANCELLED') ? array('View Request' => $class->generateUrl('view_request',  array('id' => $param ? $param['request']->getId() : null), true)) : null,
                'data'      => $data,
                'old_data'  => $oldData
            ));

            $email = self::sendEmail($class, $subject, $from, $to, $emailContent);

            if($email)
                $emailCtr++;
        }

        return $emailCtr;
    }

    /**
     * Get admin email list
     * @param array $adminList
     * @return array
     */
    public function getAdminEmails($adminList = array())
    {
        $adminEmails = array();

        if($adminList) {
            foreach($adminList as $e) {
                $email = $e->getEmail();
                $name  = $e->getUsername();
                $profile  = EmpProfileQuery::create()->filterByEmpAccAccId($e->getId())->findOne();

                if($profile) $name = $profile->getFname() . " " . $profile->getLname();

                if(! empty($email)) $adminEmails[0][$email] = "$name";
            }
        }

        return $adminEmails;
    }

    public function RequestMeetingEmail($req, $class)
    {
        $user = $class->getUser();
        $id = $user->getId();
        $empemail = $req->request->get('taggedemail');
        $empinfo = EmpProfilePeer::getInformation($req->request->get('empId'));
        $empname = $empinfo->getFname() . " " .$empinfo->getLname();

        //admin profile information
        $data = EmpProfilePeer::getInformation($id);
        $name = $data->getFname(). " " .$data->getLname();

        $subject = $req->request->get('requestname') . " " . " Request Accepted";
        $from = array('no-reply@searchoptmedia.com', 'PROLS');
        $to = array($empemail);

        $inputMessage = "<h2>Hi " . $empname . "!</h2><br><br>Your <b>" . $req->request->get('requestname') .
            "</b> request was accepted by <b>". $name .
            "</b><br><br><b>Request Info: </b><br>Date started: " . $req->request->get('datestart') .
            "<br>Date ended: ". $req->request->get('dateend');

        $email = self::sendEmail($class, $subject, $from, $to,
            $class->renderView('AdminBundle:Templates/Email:email-template.html.twig', array('message' => $inputMessage)));

        return $email ? 1: 0;
    }

    //notify user that he/she request for meeting
    public function notifyEmployeeRequest($req, $class)
    {
        $user = $class->getUser();
        $id = $user->getId();

        $empinfo = EmpProfilePeer::getInformation($id);
        $empname = $empinfo->getFname() . " " . $empinfo->getLname();


    }

    public function requestTypeEmail($req, $class, $reqId = null)
    {
        $email = 0;
        $user = $class->getUser();
        $id   = $user->getId();

        //lets get the request details
        $obj = $req->request->get('obj');
        $requestDates = array();

        foreach($obj as $o) {
            $requestDates[] = array(
                'start' => date('F d, Y', strtotime($o["start_date"])),
                'end' => date('F d, Y', strtotime($o["end_date"])),
                'reason' => $o["reason"]
            );
        }

        $empinfo = EmpProfilePeer::getInformation($id);
        $empName = $empinfo->getFname() . " " . $empinfo->getLname();

        $typeOfLeave = $req->request->get('typeleave');

        if(empty($typeOfLeave)) {
            $requestlist = ListRequestTypePeer::retrieveByPK(4);
        } else {
            $requestlist = ListRequestTypePeer::retrieveByPK($req->request->get('typeleave'));
        }

        $requestType = $requestlist->getRequestType();

        $admins = EmpAccPeer::getAdminInfo();
        $subject = "PROLS » " . $requestType . " Request";
        $from    = array('no-reply@searchoptmedia.com', 'PROLS');
        $adminEmailList = $this->getAdminEmails($admins);

        if(count($adminEmailList)) {
            $to = $adminEmailList;

            $emailContent = $class->renderView('AdminBundle:Templates/Email:email-has-table.html.twig', array(
                'data' => $requestDates,
                'title' => $requestType,
                'greetings' => 'Hi Admin,',
                'message' => "<strong>$empName</strong> has requested for a <strong>$requestType</strong>.",
                'template' => 'leave-request',
                'links' => array(count($requestDates)==1 ? 'View Request':'View All Requests' =>  $class->generateUrl('view_request',  array('id' => count($requestDates)==1? $reqId: ''), true))
            ));

            $response = self::sendEmail($class, $subject, $from, $to, $emailContent);

            if ($response)
                $email++;
        }

        return $email;
    }

    static public function sendEmail($class, $subject, $from, $to, $content)
    {
        $response = false;

        $message = new Swift_Message($subject);
        $message->setFrom($from[0]);
        $message->setBody($content, 'text/html');
        $message->setTo($to[0]);

        $response = $class->get('mailer')->send($message);

        return $response;

    }

    public function sendEmailMeetingRequest($req, $email, $class, $param = array())
    {
        $user = $class->getUser();
        $empinfo = EmpProfilePeer::getInformation($user->getId());
        $empname = $empinfo->getFname() . " " . $empinfo->getLname();

        $employee = EmpAccPeer::getUserInfo($email);
        $employee_info = EmpProfilePeer::getInformation($employee->getId());
        $employee_name = $employee_info->getFname() . " " . $employee_info->getLname();


        $from_user = $class->getUser()->getId();

        $arrlength = count($param);
        $type = $param['type'];

        $subject = "Request Meeting";
        $from    = array('no-reply@searchoptmedia.com', 'PROLS');
        $to      = array($email);
        if($type == 1) {
            $inputMessage = "Hi " . $employee_name . "!<br> ". $empname ." requested for a meeting with you. " . ".<br><br> Click <a href='http://login.propelrr.com/main/requests'>here</a> to accept/decline.";
            $email = self::sendEmail($class, $subject, $from, $to,  $class->renderView('AdminBundle:Templates/Email:email-template.html.twig',array('message' => $inputMessage)));
        }
        if($type == 2){
            $name = $param["names"];
            $inputMessage = "Hi  " . $employee_name . "!<br> You requested for  Meeting with  ". $name .".<br><br> Click <a href='http://login.propelrr.com/main/requests'>here</a> to view your request.";
            $email = self::sendEmail($class, $subject, $from, $to,  $class->renderView('AdminBundle:Templates/Email:email-template.html.twig',array('message' => $inputMessage)));
        }
        return $email ? 1: 0;
    }

    public function notifyEventEmail($req, $class, $datetimecreated, $param = array()) {
        $user = $class->getUser();
        $id   = $user->getId();
        $userProfile = EmpProfilePeer::getInformation($id);
        $userName = $userProfile->getFname() . " " . $userProfile->getLname();
        $eventType = $req->request->get('event_type');
        $name = $req->request->get('event_name');
        $fromdate = $req->request->get('from_date');
        $todate = $req->request->get('to_date');
        $email  = 0;

        if($eventType == 1) {
            $typelist = "Holiday";

            $users = EmpAccPeer::getAllUser();

            foreach($users as $user) {
                $empinfo = EmpProfilePeer::getInformation($user->getId());
                $empname = $empinfo->getFname() . " " . $empinfo->getLname();

                $subject = "PROLS » New Event";
                $from = array('no-reply@searchoptmedia.com', 'PROLS');
                $to = array($user->getEmail());
                $inputMessage = "<h2>Hi <b>" . $empname . "</b>!" . "</h2> <b>" . $userName . "</b> created a/an <b>" . $typelist . "</b> event. " .
                    "Here are the following details regarding the said event: <br><br><hr><br>";
                    if($fromdate == $todate)
                        $inputMessage .= "<b>Event Date: </b>" . $fromdate. "<br>";
                    else $inputMessage .= "<b>Event Date: </b>" . $fromdate . " to " . $todate . "<br>";

                $inputMessage .= "<b>Event Name: </b>" . $name;

                $email = self::sendEmail($class, $subject, $from, $to,
                    $class->renderView('AdminBundle:Templates/Email:email-template.html.twig', array('message' => $inputMessage)));
            }
        } else {
            $type = $param['type'];
            $emailAdd = $param['guestEmail'];
            $eventDesc = $req->request->get('event_desc');
            $eventList = ListEventsTypePeer::getEventType($eventType);
            $empinfo = EmpProfilePeer::getInformation($user->getId());
            $empname = $empinfo->getFname() . " " . $empinfo->getLname();

            $employee = EmpAccPeer::getUserInfo($emailAdd);
            $employee_info = EmpProfilePeer::getInformation($employee->getId());
            $employee_name = $employee_info->getFname() . " " . $employee_info->getLname();

            $subject = "PROLS » New Event";
            $from    = array('no-reply@searchoptmedia.com', 'PROLS');
            $to      = array($emailAdd);
            if($type == 1) {
                $inputMessage = "<h2>Hi <b>" . $employee_name . "</b>!" . "</h2> <b>" . $empname . "</b> tagged you on an event." .
                    "Here are the following details regarding the said event: <br><br><hr><br>";
                $inputMessage .= "<b>Event Type: </b>" . $eventList->getName();
                $inputMessage .= "<b>Event Name: </b>" . $name;

                if($fromdate == $todate)
                    $inputMessage .= "<b>Event Date: </b>" . $fromdate. "<br>";
                else $inputMessage .= "<b>Event Date: </b>" . $fromdate . " to " . $todate . "<br>";

                $inputMessage .= "<b>Event Description: </b>" . $eventDesc;

                $email = self::sendEmail($class, $subject, $from, $to,  $class->renderView('AdminBundle:Templates/Email:email-template.html.twig',array('message' => $inputMessage)));
            }
            if($type == 2){
                $taggednames = $param["names"];
                $inputMessage = "Hi  " . $employee_name . "!<br> You have created an event with  ". $taggednames .". " .
                    "Here are the following details regarding the said event: <br><br><hr><br>";
                $inputMessage .= "<b>Event Type: </b>" . $eventList->getName() . "<br>";
                $inputMessage .= "<b>Event Name: </b>" . $name . "<br>";

                if($fromdate == $todate)
                    $inputMessage .= "<b>Event Date: </b>" . $fromdate. "<br>";
                else $inputMessage .= "<b>Event Date: </b>" . $fromdate . " to " . $todate . "<br>";

                $inputMessage .= "<b>Event Description: </b>" . $eventDesc;
                $email = self::sendEmail($class, $subject, $from, $to,  $class->renderView('AdminBundle:Templates/Email:email-template.html.twig',array('message' => $inputMessage)));
            }
        }

        return $email ? 1: 0;
    }


}

?>