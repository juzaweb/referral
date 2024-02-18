<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

use Illuminate\Support\Str;
use Juzaweb\CMS\Models\User;

function generate_referral_code(): string
{
    do {
        $code = Str::random(8);
    } while (User::where('referral_code', $code)->exists());

    return $code;
}

function add()
{

}
 