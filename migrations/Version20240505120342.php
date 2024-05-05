<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240505120342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql("INSERT INTO payment.departments (id, name) VALUES (1, 'It')");
        $this->addSql("INSERT INTO payment.departments (id, name) VALUES (2, 'HR')");

        $this->addSql(<<<SQL
            INSERT INTO `department_bonus_rules` (`department_id`, `bonus_type`, `configuration`)
            VALUES (1, 'perentage', '{"class": "\\\\\\\App\\\\\\\Payment\\\\\\\Domain\\\\\\\Policy\\\\\\\PercentageBonusCalculationPolicy", "percent": 20}' )
            
            SQL);

                $this->addSql(<<<SQL
            INSERT INTO `department_bonus_rules` (`department_id`, `bonus_type`, `configuration`)
            VALUES (2, 'fixed', '{"class": "\\\\\\\App\\\\\\\Payment\\\\\\\Domain\\\\\\\Policy\\\\\\\FixedBonusCalculationPolicy", "bonusPerYear": 150, "maxNumberOfYear": 10}')
            SQL);

        $this->addSql("INSERT INTO payment.employees (id, name, surname, department_id, base_salary, hiring_date) VALUES (1, 'Jan', 'Kowalski', 1, 1500.00, '2022-04-30 14:17:07')");
        $this->addSql("INSERT INTO payment.employees (id, name, surname, department_id, base_salary, hiring_date) VALUES (2, 'Awesome', 'Tester', 1, 1890.00, '2013-05-04 21:07:43')");
        $this->addSql("INSERT INTO payment.employees (id, name, surname, department_id, base_salary, hiring_date) VALUES (3, 'Adam', 'Testowy', 2, 1350.00, '2015-05-05 13:59:23')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
