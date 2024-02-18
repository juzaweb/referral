<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Referral\Listeners;

use Juzaweb\Backend\Events\Users\RegisterSuccessful;

class AddCreditWhenRegisterSuccess
{
    public function handle(RegisterSuccessful $event): void
    {
        
    }
}
