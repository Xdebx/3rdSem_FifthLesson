<?php

namespace App\Listeners;

use App\Events\SendMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use \Illuminate\Http\Request;

use App\Models\Listener;
use Mail;

class SendEmailFired
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\SendMail  $event
     * @return void
     */
    public function handle(SendMail $event)
    {
        // dd($event);
        $listener = $event->listener;
        $listener = Listener::where('id',$event->listener->id)->first();
        Mail::send( 'email.user_notification', ['name' => $listener->listener_name, 'email' => $listener->email], function($message) use ($listener) {
            $message->from('admin@bands.com','Admin');
            // dd($listener);
            $message->to($listener->email, $listener->listener_name);
            $message->subject('Thank you');
            $message->attach(public_path('/images/logo.jpg'));
        });
    }
}
