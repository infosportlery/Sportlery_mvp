<?php

class InstallMessengerTables extends \October\Rain\Database\Updates\Migration
{
    public function up()
    {
        $this->run($up = true);
    }

    public function down()
    {
        $this->run($up = false);
    }

    private function run($up = true)
    {
        $updater = new \October\Rain\Database\Updater;

        $path = base_path('vendor/cmgmyr/messenger/src/migrations');
        $scripts = \File::files($path);

        \Cmgmyr\Messenger\Models\Models::setTables([
            'messages' => 'spr_chat_messages',
            'participants' => 'spr_chat_participants',
            'threads' => 'spr_chats',
        ]);

        foreach ($scripts as $script) {
            if (!ends_with($script, '.php')) {
                continue;
            }

            $migration = $updater->resolve($script);

            if ($up) {
                $migration->up($script);
            } else {
                $migration->down($script);
            }
        }
    }
}
