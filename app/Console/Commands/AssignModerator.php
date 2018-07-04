<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Validator;

class AssignModerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AssignModerator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Назначение модератора';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $email = $this->ask('Email');

        $validator = Validator::make(['email' => $email], ['email' => 'required|email|max:64']);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->comment($error);
            }
            die;
        }

        $userEmail=DB::table('users')
            ->where('email',$email)
            ->first();
        ;
        if($userEmail->email==$email){
            DB::table('users')
                ->update(['isModerator' => '1']);
            $this->info($userEmail->isModerator ? 'This user is already a moderator' : 'Moderator appointed!' );
        }
        else
            $this->info('No such user!');
    }
}
