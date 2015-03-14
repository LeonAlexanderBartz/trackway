<?php

namespace AppBundle\Tests\DependencyInjection;

use AppBundle\DependencyInjection\AppExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class AppExtensionTest extends AbstractExtensionTestCase
{
    /**
     * Return an array of container extensions you need to be registered for each test (usually just the container
     * extension you are testing.
     *
     * @return ExtensionInterface[]
     */
    protected function getContainerExtensions()
    {
        return [new AppExtension()];
    }

    /**
     * Test if all service configuration files are loaded
     */
    public function testIsLoadableAndIncludeAllFiles()
    {
        $this->load();

        // forms.xml
        $this->assertContainerBuilderHasService('app.form.factory.absence');
        $this->assertContainerBuilderHasService('app.form.factory.absence_reason');
        $this->assertContainerBuilderHasService('app.form.factory.invitation');
        $this->assertContainerBuilderHasService('app.form.factory.membership');
        $this->assertContainerBuilderHasService('app.form.factory.project');
        $this->assertContainerBuilderHasService('app.form.factory.task');
        $this->assertContainerBuilderHasService('app.form.factory.team');
        $this->assertContainerBuilderHasService('app.form.factory.time_entry');

        $this->assertContainerBuilderHasService('fos_user.change_password.form.factory');
        $this->assertContainerBuilderHasService('fos_user.group.form.factory');
        $this->assertContainerBuilderHasService('fos_user.profile.form.factory');
        $this->assertContainerBuilderHasService('fos_user.registration.form.factory');
        $this->assertContainerBuilderHasService('fos_user.resetting.form.factory');

        $this->assertContainerBuilderHasService('app.form.type.absence');
        $this->assertContainerBuilderHasService('app.form.type.absence_reason');
        $this->assertContainerBuilderHasService('app.form.type.invitation');
        $this->assertContainerBuilderHasService('app.form.type.membership');
        $this->assertContainerBuilderHasService('app.form.type.project');
        $this->assertContainerBuilderHasService('app.form.type.task');
        $this->assertContainerBuilderHasService('app.form.type.team');
        $this->assertContainerBuilderHasService('app.form.type.time_entry');

        $this->assertContainerBuilderHasService('fos_user.profile.form.type');

        // listeners.xml
        $this->assertContainerBuilderHasService('app.event_listener.routing');

        // menu.xml
        $this->assertContainerBuilderHasService('app.menu.extension.icon');
        $this->assertContainerBuilderHasService('app.menu.renderer.navbar');
        $this->assertContainerBuilderHasService('app.menu.renderer.sidebar');

        // security.xml
        $this->assertContainerBuilderHasService('app.security.authorization.voter.basetimeentry');
        $this->assertContainerBuilderHasService('app.security.authorization.voter.membership');
        $this->assertContainerBuilderHasService('app.security.authorization.voter.project');
        $this->assertContainerBuilderHasService('app.security.authorization.voter.task');
        $this->assertContainerBuilderHasService('app.security.authorization.voter.team');
    }
}