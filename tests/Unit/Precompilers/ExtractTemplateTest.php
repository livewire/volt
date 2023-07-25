<?php

use Livewire\Volt\MountedDirectories;
use Livewire\Volt\Precompilers\ExtractTemplate;

beforeEach(function () {
    $mountedDirectories = Mockery::mock(MountedDirectories::class);

    $this->precompiler = new class($mountedDirectories) extends ExtractTemplate
    {
        public function shouldExtractTemplate(string $template): bool
        {
            return true;
        }
    };
});

it('extracts the html from the given template', function () {
    $template = <<<HTML
        <?php

        use function Livewire\Volt\{state};

        state('name');

        ?>

        <div>
            <h1>{{ \$name }}</h1>
        </div>
        HTML;

    $expected = <<<'HTML'
        <div>
            <h1>{{ $name }}</h1>
        </div>
        HTML;

    expect($this->precompiler->__invoke($template))->toBe($expected);
});

it('extracts the php uses from the given template', function () {
    $template = <<<HTML
        <?php

        use App\Models\User;
        use function Livewire\Volt\{state};

        state(['name']);

        function something() use () {


        }

        ?>

        <div>
            <h1>{{ \$name ?: User::first()->name }}</h1>
        </div>
        HTML;

    $expected = <<<HTML
        <?php

        use App\Models\User;

        ?>

        <div>
            <h1>{{ \$name ?: User::first()->name }}</h1>
        </div>
        HTML;

    expect($this->precompiler->__invoke($template))->toBe($expected);
});
