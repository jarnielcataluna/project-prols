<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseListEventsQuery;

class ListEventsQuery extends BaseListEventsQuery
{
    static function _findById($id)
    {
        return
            self::create()
                ->filterById($id)
            ->findOne();
    }

    static function _findAll($params = array())
    {
        $data = self::create();

        if(!empty($params['date_started']['data'])) {
            $data->filterByFromDate($params['date_started']['data'], isset($params['date_started']['criteria']) ? $params['date_started']['criteria'] : \Criteria::GREATER_EQUAL );
        }

        if(!empty($params['date_ended']['data'])) {
            $data->filterByToDate($params['date_ended']['data'], isset($params['date_ended']['criteria']) ? $params['date_ended']['criteria'] : \Criteria::LESS_EQUAL );
        }

        if(!empty($params['tag_ids']['data'])) {
            $data->useEventTaggedPersonsQuery('tp', 'left join')
                ->filterByEmpId($params['tag_ids']['data'], isset($params['tag_ids']['criteria']) ? $params['tag_ids']['criteria'] : \Criteria::EQUAL )
                ->_or()
            ->endUse();
        }

        if(!empty($params['event_type']['data'])) {
            if(isset($params['event_type']['_or']))
                $data->_or();

            $data->filterByEventType($params['event_type']['data'], isset($params['event_type']['criteria']) ? $params['event_type']['criteria'] : \Criteria::EQUAL);
        }

        if(!empty($params['created_by']['data'])) {
            if(isset($params['created_by']['_or']))
                $data->_or();

            $data->filterByCreatedBy($params['created_by']['data'], isset($params['created_by']['criteria']) ? $params['created_by']['criteria'] : \Criteria::EQUAL);
        }

        if(!empty($params['status']['data'])) {
            if(isset($params['status']['_or']))
                $data->_or();

            $data->filterByStatus($params['status']['data'], isset($params['status']['criteria']) ? $params['status']['criteria'] : \Criteria::EQUAL);
        }

        if(!empty($params['order']['data'])) {
            $data->orderBy($params['order']['data'], isset($params['order']['criteria']) ? $params['order']['criteria'] : \Criteria::ASC);
        }

        $data->setDistinct();

        return
            $data->find();
    }
}
