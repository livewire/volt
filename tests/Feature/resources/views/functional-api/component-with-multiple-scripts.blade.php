<?php use function Livewire\Volt\state;

?>

<div>
    Hello {{ $first }}

    <?php state('first'); ?>

    Hello {{ $second }}

    <?php state(['second' => 'Otwell']); ?>
</div>
