<?php
/**
 * Static Pages Controller
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;

class PageController extends BaseController {
    public function about(Request $request): void {
        $this->render('pages.about', [
            'pageTitle' => 'About Us - Pande Computers | Kariakoo, Dar es Salaam',
        ]);
    }

    public function contact(Request $request): void {
        $this->render('pages.contact', [
            'pageTitle' => 'Contact Us - Pande Computers',
        ]);
    }

    public function privacy(Request $request): void {
        $this->render('pages.privacy', [
            'pageTitle' => 'Privacy Policy - Pande Computers',
        ]);
    }

    public function terms(Request $request): void {
        $this->render('pages.terms', [
            'pageTitle' => 'Terms and Conditions - Pande Computers',
        ]);
    }

    public function delivery(Request $request): void {
        $this->render('pages.delivery', [
            'pageTitle' => 'Delivery and Returns Policy - Pande Computers',
        ]);
    }
}
