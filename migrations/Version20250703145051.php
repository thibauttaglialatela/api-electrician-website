<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250703145051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE certification DROP FOREIGN KEY FK_6C3C6D753DA5256D');
        $this->addSql('ALTER TABLE certification ADD CONSTRAINT FK_6C3C6D753DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image CHANGE `usage` usage_type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE prestation DROP FOREIGN KEY FK_51C88FAD3DA5256D');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FAD3DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prestation DROP FOREIGN KEY FK_51C88FAD3DA5256D');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FAD3DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE image CHANGE usage_type `usage` VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE certification DROP FOREIGN KEY FK_6C3C6D753DA5256D');
        $this->addSql('ALTER TABLE certification ADD CONSTRAINT FK_6C3C6D753DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
