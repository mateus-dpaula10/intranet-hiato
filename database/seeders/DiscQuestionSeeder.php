<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiscQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            [
                "blocks" => [
                    "D" => ["Autoconfiante", 
                            "Independente", 
                            "Dominante"],
                    "I" => ["Comunicador",
                            "Alegre",
                            "Extrovertido"],                    
                    "S" => ["Acolhedor",
                            "Amigável",
                            "Paciente"],
                    "C" => ["Autodisciplinado",
                            "Atento aos detalhes",
                            "Diligente"]
                ]
            ],
            [
                "blocks" => [
                    "D" => ["Proativo",
                            "Empreendedor",
                            "Corajoso"],
                    "I" => ["Participativo",
                            "Relacional",
                            "Flexível"],                    
                    "S" => ["Agradável",
                            "Tranquilo",
                            "Organizado"],
                    "C" => ["Criterioso",
                            "Cuidadoso",
                            "Especialista"]
                ]
            ],
            [
                "blocks" => [
                    "D" => ["Prático",
                            "Rápido",
                            "Eficiente"],
                    "I" => ["Persuasivo",
                            "Contagiante",
                            "Estimulador"],                    
                    "S" => ["Calmo",
                            "Rotineiro",
                            "Constante"],
                    "C" => ["Idealizador",
                            "Perfeccionista",
                            "Uniforme"]
                ]
            ],
            [
                "blocks" => [
                    "D" => ["Objetivo",
                            "Assertivo",
                            "Foco em resultado"],
                    "I" => ["Preza pelo prazer",
                            "Emotivo",
                            "Divertido"],                    
                    "S" => ["Conciliador",
                            "Conselheiro",
                            "Bom ouvinte"],
                    "C" => ["Conforme",
                            "Sistemático",
                            "Sensato"]
                ]
            ],
            [
                "blocks" => [
                    "D" => ["Determinado",
                            "Firme",
                            "Enérgico"],
                    "I" => ["Criativo",
                            "Falante",
                            "Distraído"],                    
                    "S" => ["Comedido",
                            "Amável",
                            "Mediador"],
                    "C" => ["Preciso",
                            "Lógico",
                            "Racional"]
                ]
            ],
            [
                "blocks" => [
                    "D" => ["Lutador",
                            "Combativo",
                            "Agressivo"],
                    "I" => ["Participativo",
                            "Facilitador",
                            "Influenciador"],                    
                    "S" => ["Autocontrolado",
                            "Conservador",
                            "Responsável"],
                    "C" => ["Profundo",
                            "Perceptivo",
                            "Estratégico"]
                ]
            ],
            [
                "blocks" => [
                    "D" => ["Automotivado",
                            "Pioneiro",
                            "Impulsionador"],
                    "I" => ["Articulador",
                            "Empolgante",
                            "Motivador"],                    
                    "S" => ["Persistente",
                            "Prevenido",
                            "Tolerante"],
                    "C" => ["Exato",
                            "Exigente",
                            "Estruturado"]
                ]
            ],
            [
                "blocks" => [
                    "D" => ["Resolvedor",
                            "Destemido",
                            "Desafiador"],
                    "I" => ["Vaidoso",
                            "Simpático",
                            "Gosta de reconhecimento"],                    
                    "S" => ["Aconselhador",
                            "Harmônico",
                            "Apoiador"],
                    "C" => ["Ponderado",
                            "Ordenador",
                            "Analisador"]
                ]
            ],
            [
                "blocks" => [
                    "D" => ["Competitivo",
                            "Assume riscos",
                            "Desbravador"],
                    "I" => ["Entusiasmado",
                            "Impulsivo",
                            "Otimista"],                    
                    "S" => ["Moderado",
                            "Equilibrado",
                            "Estável"],
                    "C" => ["Teórico",
                            "Conservador",
                            "Aprofunda conhecimentos"]
                ]
            ],
        ];

        foreach ($questions as $q) {
            DB::table('disc_questions')->insert([
                'blocks'     => json_encode($q['blocks']),
                'created_at' => now(),
                'updated_at'  => now()
            ]);
        }
    }
}
