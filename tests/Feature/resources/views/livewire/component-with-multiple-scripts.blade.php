<?php use function Livewire\Volt\state;

?>

<div>
    Hello {{ $first }}
</div>

<?php state('first'); ?>

<div>
    Hello {{ $second }}
</div>

<?php state(['second' => 'Otwell']); ?>
