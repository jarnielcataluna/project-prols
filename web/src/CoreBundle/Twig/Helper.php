<?php

namespace CoreBundle\Twig;

use CoreBundle\Model\EmpAccQuery;
use CoreBundle\Model\EmpProfileQuery;
use CoreBundle\Model\EmpRequestQuery;
use CoreBundle\Model\EmpTimeQuery;
use CoreBundle\Model\ListIpQuery;
use CoreBundle\Utilities\Constant;

class Helper extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('Twig_GetUserInfo', array($this, 'getUserInfo')),
            new \Twig_SimpleFunction('Twig_GetTimeData', array($this, 'getTimeData')),
            new \Twig_SimpleFunction('Twig_GetPendingRequest', array($this, 'getPendingRequest')),
        );
    }

    public function getUserInfo($id = '', $col = 'name')
    {
        $employee = EmpProfileQuery::_findByAccId($id);
        $data = '';

        if($employee) {
            if($col=='name') $data = trim($employee->getFname().' '.$employee->getLname());
        }

        return $data;
    }

    /**
     * @param int $userID
     * @param array $info options: bday / last_timein
     * @return array
     */
    public function getTimeData($userID = 0, $info = array('bday', 'last_timein', 'ip'))
    {
        $data = array(
            'birthdays' => array(),
            'lastTimein' => array(),
            'ips' => array()
        );

        $activeEmployees = EmpProfileQuery::_findAll(array(
            'status' => Constant::STATUS_ACTIVE
        ));

        //BIRTHDAY
        if(in_array('bday', $info)) {
            if($activeEmployees) {
                foreach($activeEmployees as $e) {
                    $bday = $e->getBday()->format('m-d');
                    if($bday == date('m-d')) $data['birthdays'][] = $e->getFname();
                }
            }
        }

        //LAST TIMEIN
        if(in_array('last_timein', $info)) {
            $empAcc = EmpProfileQuery::_findByAccId($userID);
            if ($empAcc) {
                $lastTimein = EmpTimeQuery::_findAll(array(
                    'employee_id' => array('data' => $userID),
                    'table_sort' => array('data' => 'emp_time.time_in', 'criteria' => 'DESC')
                ), 'one');

                $data['lastTimein'] = $lastTimein ? $lastTimein->toArray() : array();
            }
        }

        if(in_array('ip', $info)) {
            $ips = ListIpQuery::_findAll(array('status' => array('data' => 0)));

            if($ips) {
                foreach($ips as $i) $data['ips'][] = $i->getAllowedIp();
            }
        }


        return $data;
    }

    public function getPendingRequest()
    {
        $total = EmpRequestQuery::_findAll(array(
            'status' => array('data' => Constant::STATUS_PENDING )
        ), 'count');

        return $total;
    }

    public function getName()
    {
        return 'helper';
    }
}