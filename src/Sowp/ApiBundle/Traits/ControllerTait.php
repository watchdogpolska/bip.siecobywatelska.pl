<?php
namespace Sowp\ApiBundle\Traits;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

trait ControllerTait
{
    private function getSerializer()
    {
        return $this->get('serializer');
    }

    private function getApiHelper()
    {
        return $this->get('api_helper');
    }

    private function commonLinks()
    {
        return [
            'collection_index' => $this->get('router')->generate('api_collections_list', [], Router::ABSOLUTE_URL),
            'article_index' => $this->get('router')->generate('api_article_list', [], Router::ABSOLUTE_URL),
            'messages_index' => $this->get('router')->generate('api_news_list', [], Router::ABSOLUTE_URL)
        ];
    }
}
