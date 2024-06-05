<?php
declare(strict_types=1);

namespace Proyecto\Domain\Model\Cost;

//clase donde definimos los costes de nuestras tarifas
class Cost
{
    public const int MAIN_PALLET = 400;
    public const int MAIN_SKU_IN_PALLET = 200;
    public const int MAIN_BOX = 125;
    public const int MAIN_SKU_IN_BOX = 75;
    public const int MAIN_STORAGE_MONTH = 451;
    public const int MAIN_PICKING_LOCATION = 100;
    public const int MAIN_ORDER = 40;
    public const int MAIN_SINGLE_PICKING = 30;
    public const int MAIN_MAX_PICKING_QUANTITY = 5;
    public const int MAIN_PACKING = 40;
    public const int MAIN_CUSTOM_PACKING = 100;
    public const int MAIN_AFTERSALES_RECEPTION = 40;
    public const int MAIN_SINGLE_AFTERSALES_REVIEW = 35;
    public const int MAIN_SHIPMENT_DISCOUNT = 0;
    public const int MAIN_REVERSE_SHIPMENT_DISCOUNT = 0;
    public const int MAIN_INSURANCE = 001;

    public function __construct(
        private int $pallet,
        private int $SKUInPallet,
        private int $box,
        private int $SKUInBox,
        private int $storageMonth,
        private int $pickingLocation,
        private int $order,
        private int $picking,
        private int $maxPickingQuantity,
        private int $packing,
        private int $customPacking,
        private int $aftersalesReception,
        private int $singleAftersalesReview,
        private int $shipmentDiscount,
        private int $reverseShipmentDiscount,
        private int $insurance,
    ) {
        //Left intentionally blank
    }

    public static function buildFromScratch(): self
    {
        return new self(
            self::MAIN_PALLET,
            self::MAIN_SKU_IN_PALLET,
            self::MAIN_BOX,
            self::MAIN_SKU_IN_BOX,
            self::MAIN_STORAGE_MONTH,
            self::MAIN_PICKING_LOCATION,
            self::MAIN_ORDER,
            self::MAIN_SINGLE_PICKING,
            self::MAIN_MAX_PICKING_QUANTITY,
            self::MAIN_PACKING,
            self::MAIN_CUSTOM_PACKING,
            self::MAIN_AFTERSALES_RECEPTION,
            self::MAIN_SINGLE_AFTERSALES_REVIEW,
            self::MAIN_SHIPMENT_DISCOUNT,
            self::MAIN_REVERSE_SHIPMENT_DISCOUNT,
            self::MAIN_INSURANCE,
        );
    }

    public function pallet(): int
    {
        return $this->pallet;
    }

    public function SKUInPallet(): int
    {
        return $this->SKUInPallet;
    }

    public function box(): int
    {
        return $this->box;
    }

    public function SKUInBox(): int
    {
        return $this->SKUInBox;
    }

    public function storageMonth(): int
    {
        return $this->storageMonth;
    }

    public function pickingLocation(): int
    {
        return $this->pickingLocation;
    }

    public function order(): int
    {
        return $this->order;
    }

    public function picking(): int
    {
        return $this->picking;
    }

    public function maxPickingQuantity(): int
    {
        return $this->maxPickingQuantity;
    }

    public function packing(): int
    {
        return $this->packing;
    }

    public function customPacking(): int
    {
        return $this->customPacking;
    }

    public function aftersalesReception(): int
    {
        return $this->aftersalesReception;
    }

    public function singleAftersalesReview(): int
    {
        return $this->singleAftersalesReview;
    }

    public function shipmentDiscount(): int
    {
        return $this->shipmentDiscount;
    }

    public function reverseShipmentDiscount(): int
    {
        return $this->reverseShipmentDiscount;
    }

    public function insurance(): int
    {
        return $this->insurance;
    }

}