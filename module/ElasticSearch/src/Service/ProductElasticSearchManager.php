<?php

namespace ElasticSearch\Service;

use Application\Entity\Product;

define('INDEX', 'infinishop');
define('TYPE', 'product');

class ProductElasticSearchManager extends ElasticSearchManager
{
    public function indexProduct(Product $product)
    {
        $body = [
            'name' => $product->getName(),
            'id' => $product->getId(),
            'categories' => $product->getAllCategory(),
            'colors' => $product->getColorsInWord(),
            'image' => $product->getImage(),
            'price' => $product->getPrice(),
            'intro' => $product->getIntro(),
            'keywords' => $product->getInfoKeywords(),
            'sale' => $product->getCurrentSale(),
        ];

        if ($product->getCurrentSale() > 0) {
            array_push($body['keywords'], 'sale');
        }

        $this->index(INDEX, TYPE, $product->getId(), $body);
    }

    public function updateProduct(Product $product)
    {
        $this->delete(INDEX, TYPE, $product->getId());

        $body = [
            'name' => $product->getName(),
            'id' => $product->getId(),
            'categories' => $product->getAllCategory(),
            'colors' => $product->getColorsInWord(),
            'image' => $product->getImage(),
            'price' => $product->getPrice(),
            'intro' => $product->getIntro(),
            'keywords' => $product->getInfoKeywords(),
            'sale' => $product->getCurrentSale(),
        ];

        if ($product->getCurrentSale() > 0) {
            array_push($body['keywords'], 'sale');
        }

        $this->index(INDEX, TYPE, $product->getId(), $body);
    }

    public function deleteProduct(Product $product)
    {
        $this->delete(INDEX, TYPE, $product->getId());
    }

    public function updateColor(Product $product)
    {
        $body = [
            'colors' => $product->getColorsInWord(),
        ];

        $this->update(INDEX, TYPE, $product->getId(), $body);
    }

    public function updateSale(Product $product)
    {
        $body = [
            'keywords' => $product->getInfoKeywords(),
        ];

        if ($product->getCurrentSale() > 0) {
            array_push($body['keywords'], 'sale');
        }

        $this->update(INDEX, TYPE, $product->getId(), $body);
    }

}
