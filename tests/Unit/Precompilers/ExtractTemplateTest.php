<?php

use Livewire\Volt\Exceptions\ReturnNewClassExecutionEndingException;
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

it('does not allow "return new class extends Component" ending execution', function () {
    $template = <<<'HTML'
        <?php

        return new class extends Component
        {
            //
        };

        ?>

        <div>
            <h1>{{ $name }}</h1>
        </div>

        ?>
        HTML;

    $this->precompiler->__invoke($template);
})->throws(ReturnNewClassExecutionEndingException::class);

$conflictsDataset = collect([
    [<<<'HTML'
        <?php

        use App;

        ?>

        <div/>
        HTML, <<<'HTML'
        <?php

        use App;

        ?>

        <div/>
        HTML,
    ], // ---
    [<<<'HTML'
        <?php

        use App; ?>

        <div/>
        HTML, <<<'HTML'
        <?php

        use App;

        ?>

        <div/>
        HTML
    ], // ---
    [<<<'HTML'
        <?php

        use App;
        use function Livewire\Volt\{state}; ?>

        <div/>
        HTML, <<<'HTML'
        <?php

        use App;

        ?>

        <div/>
        HTML
    ], // ---
    [<<<'HTML'
        <button />

        <?php

        use App;

        ?>

        <div/>
        HTML, <<<'HTML'
        <?php

        use App;

        ?>

        <button />



        <div/>
        HTML,
    ], // ---
    [<<<'HTML'
        <button />

        <?php

        use App; ?>

        <div/>
        HTML, <<<'HTML'
        <?php

        use App;

        ?>

        <button />



        <div/>
        HTML
    ], // ---
    [<<<'HTML'
        <button />

        <?php

        use App;
        use function Livewire\Volt\{state}; ?>

        <div/>
        HTML, <<<'HTML'
        <?php

        use App;

        ?>

        <button />



        <div/>
        HTML
    ], // ---
    [<<<'HTML'
        <button />

        <?php

        use App\User;

        ?>

        <?php

        use App;

        ?>

        <div/>
        HTML, <<<'HTML'
        <?php

        use App\User;
        use App;

        ?>

        <button />





        <div/>
        HTML,
    ], // ---
    [<<<'HTML'
        <button />

        <?php

        use App\User; ?>

        <?php

        use App; ?>

        <div/>
        HTML, <<<'HTML'
        <?php

        use App\User;
        use App;

        ?>

        <button />





        <div/>
        HTML
    ], // ---
    [<<<'HTML'
        <button />

        <?php

        use App\User;

        ?>

        <?php

        use App;
        use function Livewire\Volt\{state}; ?>

        <div/>
        HTML, <<<'HTML'
        <?php

        use App\User;
        use App;

        ?>

        <button />





        <div/>
        HTML
    ], // ---
    [<<<'HTML'
        <button />

        <?php

        use App\User;

        ?>

        <div/>

        <?php

        use App;
        HTML, <<<'HTML'
        <?php

        use App\User;

        ?>

        <button />



        <div/>
        HTML,
    ], // ---
    [<<<'HTML'
        <button />

        <?php

        use App\User; ?>

        <div/>

        <?php

        use App; ?>
        HTML, <<<'HTML'
        <?php

        use App\User;
        use App;

        ?>

        <button />



        <div/>
        HTML
    ], // ---
    [<<<'HTML'
        <?php

        use App\User; ?>

        <button />

        <div/>

        <?php

        use App;
        use function Livewire\Volt\{state};
        HTML, <<<'HTML'
        <?php

        use App\User;

        ?>

        <button />

        <div/>
        HTML
    ], // ---

])->keyBy(function (array $value) {
    return htmlspecialchars($value[0]);
})->toArray();

test('import conflicts', function (string $template, string $expected) {
    $result = $this->precompiler->__invoke($template);

    expect(trim($result))->toBe($expected);
})->with($conflictsDataset);
