<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Elasticsearch\SearchAdapter;

use Magento\Framework\Search\RequestInterface;
use Magento\Framework\Search\Request\QueryInterface as RequestQueryInterface;
use Magento\Framework\Search\Request\Query\BoolExpression as BoolQuery;
use Magento\Framework\Search\Request\Query\Filter as FilterQuery;
use Magento\Framework\Search\Request\Query\Match as MatchQuery;
use Magento\Elasticsearch\SearchAdapter\Query\Builder as QueryBuilder;
use Magento\Elasticsearch\SearchAdapter\Query\Builder\Match as MatchQueryBuilder;
use Magento\Elasticsearch\SearchAdapter\Filter\Builder as FilterBuilder;
use Magento\Elasticsearch\Elasticsearch5\SearchAdapter\Mapper as ES5Mapper;

/**
 * Mapper class
 * @api
 * @since 100.1.0
 */
class Mapper extends ES5Mapper
{

    /**
     * Build adapter dependent query
     *
     * @param RequestInterface $request
     * @return array
     * @since 100.1.0
     */
    public function buildQuery(RequestInterface $request)
    {
        $searchQuery = $this->queryBuilder->initQuery($request);
        $searchQuery['body']['query'] = array_merge(
            $searchQuery['body']['query'],
            $this->processQuery(
                $request->getQuery(),
                [],
                BoolQuery::QUERY_CONDITION_MUST
            )
        );

        $searchQuery['body']['query']['bool']['minimum_should_match'] = 1;

        $searchQuery = $this->queryBuilder->initAggregations($request, $searchQuery);
        return $searchQuery;
    }
}
