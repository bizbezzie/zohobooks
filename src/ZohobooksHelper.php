<?php

namespace Bizbezzie\Zohobooks;

class ZohobooksHelper
{
    public function addItemToLineItems(
        array  $line_items_array,
        string $item_id,
        int    $quantity = 1,
        int    $rate = null,
        string $discount = null,
        int    $item_order = null,
        string $description = null
    ): array
    {
        $line_item = [
            'item_id'         => $item_id,
            'quantity'        => $quantity,
            'rate'            => $rate,
            'discount'        => $discount,
            'item_order'      => $item_order,
            'description'     => $description,
        ];

        $line_items_array[] = $line_item;

        return $line_items_array;
    }
}
