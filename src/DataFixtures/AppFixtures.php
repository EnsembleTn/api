<?php

namespace App\DataFixtures;

use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $questions = [
            [
                "fr_value" => "Étiez vous à l’étranger récemment ?",
                "ar_value" => "هل كنت  في الخارج مؤخرًا ؟",
                "type" => Question::TYPE_NORMAL,
                "category" => Question::CATEGORY_GENERAL
            ],
            [
                "fr_value" => "Étiez vous en contact avec une personne venant de l’étranger ?",
                "ar_value" => "هل كنت على اتصال بشخص قادم  من الخارج ؟",
                "type" => Question::TYPE_NORMAL,
                "category" => Question::CATEGORY_GENERAL
            ],
            [
                "fr_value" => "Étiez en contact avec une personne qui a été testée positive ?",
                "ar_value" => "هل كنت على اتصال بشخص كانت نتيجة اختباره إيجابية ؟",
                "type" => Question::TYPE_NORMAL,
                "category" => Question::CATEGORY_GENERAL
            ],
            [
                "fr_value" => "Quel est votre âge ?",
                "ar_value" => "كم عمرك ؟",
                "type" => Question::TYPE_NORMAL,
                "category" => Question::CATEGORY_GENERAL
            ],
            [
                "fr_value" => "Êtes vous diabétique ? (oui, non)",
                "ar_value" => "هل تعاني من مرض السكري ؟",
                "type" => Question::TYPE_YES_OR_NO,
                "category" => Question::CATEGORY_ANTECEDENT
            ],
            [
                "fr_value" => "Avez vous une maladie respiratoire ? êtes vous suivi par un pneumologue ? (oui, non)",
                "ar_value" => "هل تعاني من مرض تنفسي؟ هل يتابعك طبيب مختص في أمراض الرئة ؟",
                "type" => Question::TYPE_YES_OR_NO,
                "category" => Question::CATEGORY_ANTECEDENT
            ],
            [
                "fr_value" => "Avez-vous de l’hypertension artérielle ? Ou avez-vous une maladie cardiaque ou vasculaire ? Ou prenez-vous un traitement à visée cardiologique ? (oui, non, je ne sais pas)",
                "ar_value" => "هل تعاني من ارتفاع ضغط الدم؟ أو لديك مرض القلب أو الأوعية الدموية؟ هل  تأخذ علاج للقلب ؟",
                "type" => Question::TYPE_YES_OR_NO_NEUTRAL,
                "category" => Question::CATEGORY_ANTECEDENT
            ],
            [
                "fr_value" => "Avez-vous une insuffisance rénale chronique dialysée ? (oui, non)",
                "ar_value" => "هل تعاني من قصور كلوي ؟",
                "type" => Question::TYPE_YES_OR_NO,
                "category" => Question::CATEGORY_ANTECEDENT
            ],
            [
                "fr_value" => "Avez-vous une maladie chronique de foie ? (oui, non)",
                "ar_value" => "هل لديك مرض مزمن في الكبد ؟",
                "type" => Question::TYPE_YES_OR_NO,
                "category" => Question::CATEGORY_ANTECEDENT
            ],
            [
                "fr_value" => "Avez vous ou avez vous eu un cancer ? (oui, non)",
                "ar_value" => "هل لديك أو كان  لديك مرض سرطان ؟",
                "type" => Question::TYPE_YES_OR_NO,
                "category" => Question::CATEGORY_ANTECEDENT
            ],
            [
                "fr_value" => "Êtes vous enceinte ?(oui, non, non applicable) ?",
                "ar_value" => "هل أنت حامل ؟",
                "type" => Question::TYPE_YES_OR_NO_NOT_APPLICABLE,
                "category" => Question::CATEGORY_ANTECEDENT
            ],
            [
                "fr_value" => "Avez-vous une maladie connue diminuer vos défenses immunitaires ? (oui, non)",
                "ar_value" => "هل تعاني من مرض ينقص من مناعتك؟",
                "type" => Question::TYPE_YES_OR_NO,
                "category" => Question::CATEGORY_ANTECEDENT
            ],
            [
                "fr_value" => "Prenez vous un traitement immunosuppresseur ? (oui, non)",
                "ar_value" => "هل تتناول علاج مثبط للمناعة ؟",
                "type" => Question::TYPE_YES_OR_NO,
                "category" => Question::CATEGORY_ANTECEDENT
            ],
            [
                "fr_value" => "Avez-vous de la fièvre, des frissons, des sueurs ? (oui, non)",
                "ar_value" => "هل تعاني من الحمى والرعشة والتعرق ؟",
                "type" => Question::TYPE_YES_OR_NO,
                "category" => Question::CATEGORY_SYMPTOMS
            ],
            [
                "fr_value" => "Si oui indiquez la temperature ?",
                "ar_value" => "إذا كان الجواب نعم ، حدد درجة الحرارة؟",
                "type" => Question::TYPE_NORMAL,
                "category" => Question::CATEGORY_SYMPTOMS
            ],
            [
                "fr_value" => "Avez-vous une toux ou une augmentation de votre toux habituelle ces derniers jours ? (oui, non)",
                "ar_value" => "هل تعاني من السعال  أو زيادة في السعال المعتاد  في الأيام القليلة الماضية ؟",
                "type" => Question::TYPE_YES_OR_NO,
                "category" => Question::CATEGORY_SYMPTOMS
            ],
            [
                "fr_value" => "Avez-vous un mal de gorge apparu ces derniers jours ? (oui, non)",
                "ar_value" => "هل أصبت بالتهاب حنجرة  في الأيام الأخيرة ؟",
                "type" => Question::TYPE_YES_OR_NO,
                "category" => Question::CATEGORY_SYMPTOMS
            ],
            [
                "fr_value" => "Avez-vous des maux de tête ? (oui, non)",
                "ar_value" => "هل تعاني من آلام بالرأس ؟",
                "type" => Question::TYPE_YES_OR_NO,
                "category" => Question::CATEGORY_SYMPTOMS
            ],
            [
                "fr_value" => "Avez-vous des douleurs musculaires ou des courbatures inhabituelles ces derniers jours ? (oui, non)",
                "ar_value" => "هل عانيت من آلام أو آلام عضلية غير معتادة في الأيام القليلة الماضية ؟",
                "type" => Question::TYPE_YES_OR_NO,
                "category" => Question::CATEGORY_SYMPTOMS
            ],
            [
                "fr_value" => "Avez-vous une fatigue inhabituelle ces derniers jours ? (oui, non)",
                "ar_value" => "هل تعاني من تعب غير معتاد  في الأيام الأخيرة ؟",
                "type" => Question::TYPE_YES_OR_NO,
                "category" => Question::CATEGORY_SYMPTOMS
            ],
            [
                "fr_value" => "Avez-vous une gêne respiratoire ou une augmentation de votre gêne respiratoire inhabituelle ? (oui, non)",
                "ar_value" => "هل  تعاني من صعوبة في التنفس ؟",
                "type" => Question::TYPE_YES_OR_NO,
                "category" => Question::CATEGORY_SYMPTOMS
            ],
        ];


        foreach ($questions as $questionEntry) {
            $question = new Question();

            $question
                ->setFrValue($questionEntry['fr_value'])
                ->setArValue($questionEntry['ar_value'])
                ->setType($questionEntry['type'])
                ->setCategory($questionEntry['category']);

            $manager->persist($question);
        }

        $manager->flush();
    }
}
