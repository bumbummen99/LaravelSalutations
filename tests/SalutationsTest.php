<?php

namespace SkyRaptor\Tests\LaravelSalutations;

use Orchestra\Testbench\TestCase;
use SkyRaptor\LaravelSalutations\Salutations;
use SkyRaptor\LaravelSalutations\SalutationsServiceProvider;

class SalutationsTest extends TestCase
{
    /**
     * Set the package service provider.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [SalutationsServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup LaravelSalutations specific config values
        $app['config']->set('salutations.fallback_locale', 'en');
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function can_change_config_values()
    {
        $this->app['config']->set('salutations.fallback_locale', 'en');
        $this->assertEquals('en', $this->app['config']->get('salutations.fallback_locale'));
    }

    /** @test */
    public function can_get_salutations_by_shorthand()
    {
        $salutations = new Salutations();

        $this->assertEquals('Mister', $salutations->male());
        $this->assertEquals('Ms.', $salutations->female());
        $this->assertEquals('Other', $salutations->other());
    }

    /** @test */
    public function can_get_salutations_by_index()
    {
        $salutations = new Salutations();

        $this->assertEquals('Mister', $salutations->index(0));
        $this->assertEquals('Ms.', $salutations->index(1));
        $this->assertEquals('Other', $salutations->index(2));
    }

    /** @test */
    public function can_get_salutations_by_key()
    {
        $salutations = new Salutations();

        $this->assertEquals('Mister', $salutations->key('male'));
        $this->assertEquals('Ms.', $salutations->key('female'));
        $this->assertEquals('Other', $salutations->key('other'));
    }

    /** @test */
    public function can_get_salutations_lookup()
    {
        $salutations = new Salutations();

        $this->assertEquals(true, is_array($salutations->lookup()));
        $this->assertEquals(true, count($salutations->lookup()) > 0);
    }

    /** @test */
    public function can_get_salutations_lookup_include()
    {
        $salutations = new Salutations();

        $lookup = $salutations->lookupInclude([0, 'female']);

        $this->assertEquals(2, count($lookup));
        foreach ($lookup as $i => $e) {
            $this->assertEquals(true, $i == 0 || $i == 1);
        }
    }

    /** @test */
    public function can_get_salutations_lookup_exclude()
    {
        $salutations = new Salutations();

        $lookup = $salutations->lookupExclude([0, 'female']);
        $numExpected = count($salutations->lookup()) - 2;

        $this->assertEquals($numExpected, count($lookup));
        foreach ($lookup as $i => $e) {
            $this->assertEquals(false, $i == 0 || $i == 1);
        }
    }

    /** @test */
    public function lookup_can_take_nonsense_filters()
    {
        $salutations = new Salutations();

        $lookup = $salutations->lookupExclude([9999, 'doesNotExist']);
        $this->assertEquals(count($salutations->lookup()), count($lookup));
    }

    /** @test */
    public function can_change_target_language()
    {
        $salutations = new Salutations();
        $salutations->setLocale('de');

        $this->assertEquals('Herr', $salutations->male());
    }

    /**
     * @test
     * @expectedException SkyRaptor\LaravelSalutations\Exceptions\InvalidIndexException
     */
    public function will_verify_salutation_index()
    {
        $salutations = new Salutations();

        $test = $salutations->index(9999);
    }

    /**
     * @test
     * @expectedException SkyRaptor\LaravelSalutations\Exceptions\InvalidKeyException
     */
    public function will_verify_salutation_key()
    {
        $salutations = new Salutations();

        $test = $salutations->key('doesNotExist');
    }
}
