<?php

namespace App\Http\Controllers;

class VendorKonstruksiController extends VendorTiangController
{
    protected function routePrefix(): string { return 'vendor_konstruksi'; }
    protected function recipientRole(): string { return 'konstruksi'; }
    protected function sentColumn(): string { return 'konstruksi_vendor_sent'; }
    protected function sentAtColumn(): string { return 'konstruksi_vendor_sent_at'; }
    protected function pageTitle(): string { return 'Vendor Konstruksi'; }
}
