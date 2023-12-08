<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231207085649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders ADD items JSON NOT NULL');
        $this->addSql('ALTER TABLE shopping_cart DROP FOREIGN KEY shopping_cart_relation_1');
        $this->addSql('DROP INDEX shopping_cart_relation_1 ON shopping_cart');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT shopping_cart_relation_1 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('CREATE INDEX shopping_cart_relation_1 ON shopping_cart (user_id)');
        $this->addSql('ALTER TABLE orders DROP items');
    }
}
