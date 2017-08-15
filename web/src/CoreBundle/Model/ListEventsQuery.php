<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseListEventsQuery;
use CoreBundle\Utilities\Constant;

class ListEventsQuery extends BaseListEventsQuery
{
    static function _findById($id)
    {
        return
            self::create()
                ->filterById($id)
            ->findOne();
    }

    static function _findAll($params = array(), $isCount = false)
    {
        $data = self::create();
        $isHoliday = false;

        if(!empty($params['date_started']['data'])) {
            $data->filterByFromDate($params['date_started']['data'], isset($params['date_started']['criteria']) ? $params['date_started']['criteria'] : \Criteria::GREATER_EQUAL );
        }

        if(!empty($params['date_ended']['data'])) {
            $data->_or();
            $data->filterByToDate($params['date_ended']['data'], isset($params['date_ended']['criteria']) ? $params['date_ended']['criteria'] : \Criteria::LESS_EQUAL );
        }

        if(!empty($params['event_type']['data'])) {
            if(isset($params['event_type']['_or']))
                $data->_or();

            if($params['event_type']['data']==Constant::EVENT_TYPE_HOLIDAY) {
                $isHoliday = true;
            }

            $data->filterByEventType($params['event_type']['data'], isset($params['event_type']['criteria']) ? $params['event_type']['criteria'] : \Criteria::EQUAL);
        }

        if($isHoliday==false) {
            if (!empty($params['tag_ids']['data'])) {
                $data->useEventTaggedPersonsQuery('tp', 'left join')
                    ->filterByEmpId($params['tag_ids']['data'], isset($params['tag_ids']['criteria']) ? $params['tag_ids']['criteria'] : \Criteria::EQUAL)
                    ->_or()
                    ->endUse();
            }

            if (!empty($params['tag_status']['data'])) {
                $data->useEventTaggedPersonsQuery('tp', 'left join')
                    ->filterByStatus($params['tag_status']['data'], isset($params['tag_status']['criteria']) ? $params['tag_status']['criteria'] : \Criteria::EQUAL)
                    ->_or()
                    ->endUse();
            }
        }
//        $data->_or();
//        $data->filterByEventType(1, isset($params['event_type']['criteria']) ? $params['event_type']['criteria'] : \Criteria::EQUAL);

        if(!empty($params['created_by']['data'])) {
            if(isset($params['created_by']['_or']) && $params['created_by']['_or']==true)
                $data->_or();

            $data->filterByCreatedBy($params['created_by']['data'], isset($params['created_by']['criteria']) ? $params['created_by']['criteria'] : \Criteria::EQUAL);
        }

        if(!empty($params['status']['data'])) {
            if(isset($params['status']['_or']))
                $data->_or();

            $data->filterByStatus($params['status']['data'], isset($params['status']['criteria']) ? $params['status']['criteria'] : \Criteria::EQUAL);
        }

        if(!empty($params['searchText'])) {
            $data->filterByEventName('%'.$params['searchText'].'%', \Criteria::LIKE);
            $data->_or();
            $data->filterByEventDescription('%'.$params['searchText'].'%', \Criteria::LIKE);
            $data->_or();
            $data
                ->useListEventsTypeQuery()
                ->filterByName('%'.$params['searchText'].'%', \Criteria::LIKE)
                ->endUse();
        }

        if(!empty($params['order']['data'])) {
            if($params['order']['data']!='event_type') $data->orderBy($params['order']['data'], isset($params['order']['criteria']) ? $params['order']['criteria'] : \Criteria::ASC);
            else $data->orderByEventType($params['order']['criteria']);
        }

        if(isset($params['page'])) {
            $data->setOffset($params['page']);
            $data->setLimit($params['limit']);
        }

        $data->setDistinct();

        if($isCount)
            return $data->count();

        return
            $data->find();
    }
}
