<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211115145241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD registration_token BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', ADD new_password_token BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', DROP registrationtoken, DROP new_passwordtoken');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD registrationtoken BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', ADD new_passwordtoken BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', DROP registration_token, DROP new_password_token');
    }
}
