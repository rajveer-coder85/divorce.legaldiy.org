<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class KnowledgeController extends Controller
{
    public function show(string $guide): View
    {
        if ($guide === 'children-maintenance') {
            return view('knowledge.children-maintenance');
        }

        $guides = [
            'property' => [
                'eyebrow' => 'Property',
                'title' => 'Understand what needs to happen to shared property.',
                'intro' => 'Start by identifying assets, ownership, financing, and the practical steps needed for any transfer or sale.',
                'sections' => [
                    ['Identify the property', 'List the matrimonial home and any other property that needs to be considered.'],
                    ['Ownership and financing', 'Record whose names are on the title and financing, together with any outstanding balance.'],
                    ['Keep, transfer, or sell', 'Discuss the intended outcome, timing, valuation, costs, and what must happen before completion.'],
                    ['Division and documents', 'Clarify how proceeds or interests may be divided and which supporting records should be gathered.'],
                ],
                'example' => 'One spouse keeps the matrimonial home, subject to an agreed valuation, financing arrangements, and transfer steps.',
            ],
            'alimony' => [
                'eyebrow' => 'Alimony & spousal maintenance',
                'title' => 'Discuss whether financial support between spouses is needed.',
                'intro' => 'Alimony is also commonly described as spousal maintenance. This guide helps structure the financial conversation.',
                'sections' => [
                    ['Current financial position', 'Consider each spouse’s income, essential expenses, commitments, and available resources.'],
                    ['Amount and affordability', 'Discuss what support may be needed and what the paying spouse can realistically afford.'],
                    ['Timing and duration', 'Consider when payments would begin, how often they would be made, and whether an end point is intended.'],
                    ['Changes and review', 'Discuss how significant changes in circumstances would be handled and recorded.'],
                ],
                'example' => 'One spouse provides monthly support for an agreed period while the other works towards greater financial independence.',
            ],
            'joint-petition' => [
                'eyebrow' => 'Joint petition & court process',
                'title' => 'See how an agreement moves into the court journey.',
                'intro' => 'This overview helps you recognise the preparation, filing, hearing, and finalisation stages that follow an agreement.',
                'sections' => [
                    ['Prepare the information', 'Bring together personal details and the arrangements agreed for children, property, and maintenance.'],
                    ['Review and signing', 'Check that the documents reflect the agreement before completing the required signing and affirmation steps.'],
                    ['Filing and hearing', 'The documents enter the court process and the spouses attend the required hearing.'],
                    ['Orders and finalisation', 'Complete the remaining order and finalisation steps, including subsequent registration where required.'],
                ],
                'example' => 'Both spouses settle the practical terms first, review the prepared documents, and then proceed through the court stages together.',
            ],
        ];

        abort_unless(isset($guides[$guide]), 404);

        return view('knowledge.show', ['guide' => $guides[$guide]]);
    }
}
