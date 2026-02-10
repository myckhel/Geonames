<?php

namespace MichaelDrennen\Geonames\Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;

class InstallIsoLanguageCodeTest extends BaseInstallTestCase {

    #[Group('install')]
    #[Group('iso')]
    #[Test]
    public function testIsoLanguageCodeCommand() {
        $this->artisan( 'geonames:iso-language-code', [ '--connection' => $this->DB_CONNECTION ] );
        $isoLanguageCodes = \MichaelDrennen\Geonames\Models\IsoLanguageCode::all();
        $this->assertInstanceOf( \Illuminate\Support\Collection::class, $isoLanguageCodes );
        $this->assertNotEmpty( $isoLanguageCodes );
        $this->assertInstanceOf( \MichaelDrennen\Geonames\Models\IsoLanguageCode::class, $isoLanguageCodes->first() );
    }


    #[Group('install')]
    #[Group('iso')]
    #[Test]
    public function testIsoLanguageCodeCommandFailureWithNonExistentConnection() {
        $this->expectException( \Exception::class );
        $this->artisan( 'geonames:iso-language-code', [ '--connection' => 'i-dont-exist' ] );
    }


}