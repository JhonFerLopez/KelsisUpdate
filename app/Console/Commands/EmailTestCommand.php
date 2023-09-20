<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;

class EmailTestCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'email:test';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Send a test email.';

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    $to = 'your@email.com';
    $subject = 'Bagisto Email Test';
    $body = 'This is a test email from Bagisto.';

    //Mail::to($to)->subject($subject)->send($body);

    $this->info('Test email sent successfully.');
  }
}