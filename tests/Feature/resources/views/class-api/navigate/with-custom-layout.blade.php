<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\View\View;

new #[Layout('components.layouts.custom')] class extends Component
{
    public $content = 'content with custom layout';

    public function rendering(View $view): View
    {
        return $view->title('custom title');
    }
}; ?>

?>

<div>
    <h1>Content: {{ $content }}.</h1>
</div>
