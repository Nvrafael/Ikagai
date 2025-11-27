<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Support\Str;

class ResourcesSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener el primer admin o nutricionista como autor
        $author = User::whereIn('role', ['admin', 'nutritionist'])->first();
        
        if (!$author) {
            $author = User::where('role', 'client')->first();
        }

        if (!$author) {
            $this->command->error('No hay usuarios en la base de datos. Ejecuta primero los seeders de usuarios.');
            return;
        }

        // RECETAS
        $recetas = [
            [
                'title' => 'Ensalada de Quinoa y Aguacate',
                'type' => 'receta',
                'excerpt' => 'Una ensalada nutritiva y deliciosa, perfecta para una comida ligera y saludable.',
                'content' => '<h2>Preparación</h2>
<p>Esta ensalada combina la proteína de la quinoa con las grasas saludables del aguacate, creando un plato completo y nutritivo.</p>

<h3>Pasos:</h3>
<ol>
<li>Cocina la quinoa según las instrucciones del paquete y déjala enfriar.</li>
<li>Corta el aguacate en cubos y mezcla con la quinoa.</li>
<li>Añade tomates cherry cortados por la mitad, pepino en rodajas y cilantro fresco.</li>
<li>Prepara un aderezo con aceite de oliva, jugo de limón, sal y pimienta.</li>
<li>Mezcla todo y sirve frío.</li>
</ol>

<p>Esta receta es ideal para preparar con anticipación y llevar al trabajo o la universidad.</p>',
                'metadata' => [
                    'ingredientes' => "- 1 taza de quinoa\n- 1 aguacate maduro\n- 10 tomates cherry\n- 1/2 pepino\n- Cilantro fresco\n- 2 cucharadas de aceite de oliva\n- Jugo de 1 limón\n- Sal y pimienta al gusto",
                    'tiempo_preparacion' => '20 minutos',
                    'porciones' => '2 personas',
                    'dificultad' => 'facil',
                    'calorias' => '350 kcal por porción',
                ],
            ],
            [
                'title' => 'Smoothie Bowl de Frutas',
                'type' => 'receta',
                'excerpt' => 'Un desayuno energético y colorido que te llenará de vitaminas para empezar el día.',
                'content' => '<h2>Preparación</h2>
<p>Los smoothie bowls son perfectos para un desayuno nutritivo y visualmente atractivo.</p>

<h3>Base del smoothie:</h3>
<ol>
<li>Congela previamente los plátanos y las fresas.</li>
<li>Mezcla en la licuadora: 2 plátanos congelados, 1 taza de fresas, 1/2 taza de leche de almendras.</li>
<li>Licúa hasta obtener una consistencia cremosa.</li>
<li>Vierte en un bowl.</li>
</ol>

<h3>Toppings sugeridos:</h3>
<ul>
<li>Granola casera</li>
<li>Rodajas de plátano fresco</li>
<li>Arándanos</li>
<li>Semillas de chía</li>
<li>Coco rallado</li>
<li>Mantequilla de almendras</li>
</ul>',
                'metadata' => [
                    'ingredientes' => "- 2 plátanos congelados\n- 1 taza de fresas congeladas\n- 1/2 taza de leche de almendras\n- Granola\n- Frutas frescas variadas\n- Semillas de chía\n- Coco rallado",
                    'tiempo_preparacion' => '10 minutos',
                    'porciones' => '1 persona',
                    'dificultad' => 'facil',
                    'calorias' => '320 kcal',
                ],
            ],
            [
                'title' => 'Pollo al Horno con Vegetales',
                'type' => 'receta',
                'excerpt' => 'Una comida completa y balanceada, ideal para preparar en familia.',
                'content' => '<h2>Preparación</h2>
<p>Este plato es perfecto para una cena familiar nutritiva y fácil de preparar.</p>

<h3>Instrucciones:</h3>
<ol>
<li>Precalienta el horno a 200°C.</li>
<li>Marina las pechugas de pollo con limón, ajo, romero, sal y pimienta por 30 minutos.</li>
<li>Corta las zanahorias, pimientos y calabacín en trozos grandes.</li>
<li>Coloca el pollo y los vegetales en una bandeja de horno.</li>
<li>Rocía con aceite de oliva y hornea por 35-40 minutos.</li>
<li>Sirve caliente acompañado de arroz integral o quinoa.</li>
</ol>',
                'metadata' => [
                    'ingredientes' => "- 4 pechugas de pollo\n- 3 zanahorias\n- 2 pimientos rojos\n- 1 calabacín\n- Jugo de 2 limones\n- 3 dientes de ajo\n- Romero fresco\n- Aceite de oliva\n- Sal y pimienta",
                    'tiempo_preparacion' => '1 hora',
                    'porciones' => '4 personas',
                    'dificultad' => 'media',
                    'calorias' => '280 kcal por porción',
                ],
            ],
        ];

        // CONSEJOS
        $consejos = [
            [
                'title' => '5 Hábitos para Mejorar tu Digestión',
                'type' => 'consejo',
                'excerpt' => 'Pequeños cambios en tu rutina diaria que pueden hacer una gran diferencia en tu salud digestiva.',
                'content' => '<h2>Mejora tu Digestión con Estos Consejos</h2>
<p>La digestión es un proceso fundamental para nuestra salud general. Aquí te compartimos 5 hábitos que puedes incorporar fácilmente:</p>

<h3>1. Mastica bien tus alimentos</h3>
<p>La digestión comienza en la boca. Masticar adecuadamente facilita el trabajo del estómago y mejora la absorción de nutrientes.</p>

<h3>2. Mantente hidratado</h3>
<p>Beber suficiente agua durante el día ayuda a mantener el sistema digestivo funcionando correctamente. Intenta beber al menos 8 vasos de agua al día.</p>

<h3>3. Come más fibra</h3>
<p>Incluye frutas, verduras, legumbres y granos enteros en tu dieta. La fibra mejora el tránsito intestinal.</p>

<h3>4. Evita comer tarde por la noche</h3>
<p>Deja al menos 2-3 horas entre tu última comida y la hora de dormir para permitir una digestión adecuada.</p>

<h3>5. Reduce el estrés</h3>
<p>El estrés afecta directamente tu sistema digestivo. Practica técnicas de relajación como yoga o meditación.</p>',
                'metadata' => null,
            ],
            [
                'title' => 'Cómo Leer las Etiquetas Nutricionales',
                'type' => 'consejo',
                'excerpt' => 'Aprende a interpretar la información nutricional de los productos para tomar mejores decisiones.',
                'content' => '<h2>Guía para Entender las Etiquetas</h2>
<p>Saber leer las etiquetas nutricionales es fundamental para hacer compras inteligentes y cuidar tu salud.</p>

<h3>Lo que debes revisar primero:</h3>

<h4>1. Tamaño de la porción</h4>
<p>Verifica cuántas porciones contiene el envase. Los valores nutricionales se refieren a una porción, no al paquete completo.</p>

<h4>2. Calorías</h4>
<p>Considera cuántas calorías aporta una porción y compáralo con tus necesidades diarias.</p>

<h4>3. Grasas</h4>
<p>Limita las grasas saturadas y evita las grasas trans. Busca productos con grasas saludables (mono y poliinsaturadas).</p>

<h4>4. Sodio</h4>
<p>El exceso de sodio puede aumentar la presión arterial. Busca productos con menos de 140mg por porción.</p>

<h4>5. Azúcares añadidos</h4>
<p>Prefiere productos con bajo contenido de azúcares añadidos. Los azúcares naturales de frutas son una mejor opción.</p>

<h4>6. Fibra y proteína</h4>
<p>Busca productos altos en fibra (más de 3g) y proteína para mayor saciedad.</p>',
                'metadata' => null,
            ],
        ];

        // ARTÍCULOS
        $articulos = [
            [
                'title' => 'Los Beneficios del Ayuno Intermitente',
                'type' => 'articulo',
                'excerpt' => 'Descubre cómo esta práctica milenaria puede mejorar tu salud metabólica y ayudarte a alcanzar tus objetivos.',
                'content' => '<h2>¿Qué es el Ayuno Intermitente?</h2>
<p>El ayuno intermitente no es una dieta, sino un patrón de alimentación que alterna períodos de ayuno con períodos de alimentación.</p>

<h3>Métodos más populares:</h3>

<h4>Método 16/8</h4>
<p>Consiste en ayunar durante 16 horas y comer durante una ventana de 8 horas. Es el más popular y fácil de seguir.</p>

<h4>Método 5:2</h4>
<p>Comes normalmente 5 días a la semana y reduces la ingesta calórica (500-600 calorías) durante 2 días no consecutivos.</p>

<h3>Beneficios respaldados por la ciencia:</h3>
<ul>
<li>Mejora la sensibilidad a la insulina</li>
<li>Promueve la pérdida de peso y grasa corporal</li>
<li>Reduce la inflamación</li>
<li>Mejora la salud cardiovascular</li>
<li>Puede aumentar la longevidad</li>
<li>Favorece la autofagia celular</li>
</ul>

<h3>Consideraciones importantes:</h3>
<p>El ayuno intermitente no es para todos. Consulta con un profesional de la salud antes de comenzar, especialmente si:</p>
<ul>
<li>Tienes diabetes o problemas de azúcar en sangre</li>
<li>Estás embarazada o amamantando</li>
<li>Tienes historial de trastornos alimentarios</li>
<li>Tomas medicamentos específicos</li>
</ul>',
                'metadata' => null,
            ],
            [
                'title' => 'Superalimentos: Mito o Realidad',
                'type' => 'articulo',
                'excerpt' => 'Analizamos la ciencia detrás de los llamados "superalimentos" y su verdadero impacto en la salud.',
                'content' => '<h2>¿Qué son los Superalimentos?</h2>
<p>El término "superalimento" es más de marketing que científico. Sin embargo, ciertos alimentos sí tienen un perfil nutricional excepcional.</p>

<h3>Alimentos con Beneficios Comprobados:</h3>

<h4>Arándanos</h4>
<p>Ricos en antioxidantes, especialmente antocianinas, que pueden mejorar la memoria y reducir el riesgo de enfermedades cardíacas.</p>

<h4>Espinaca y Kale</h4>
<p>Excelentes fuentes de vitaminas K, A, C y folato. Alto contenido de hierro y calcio.</p>

<h4>Salmón</h4>
<p>Fuente de omega-3, proteína de alta calidad y vitamina D. Beneficioso para la salud cerebral y cardiovascular.</p>

<h4>Nueces</h4>
<p>Contienen grasas saludables, proteína, fibra y antioxidantes. Asociadas con mejor salud cardíaca.</p>

<h4>Cúrcuma</h4>
<p>La curcumina tiene potentes propiedades antiinflamatorias y antioxidantes.</p>

<h3>La Verdad:</h3>
<p>No existe un alimento milagroso. La clave está en una dieta variada y equilibrada que incluya muchos alimentos nutritivos diferentes.</p>

<p>En lugar de obsesionarte con superalimentos específicos, enfócate en:</p>
<ul>
<li>Comer una variedad de frutas y verduras coloridas</li>
<li>Incluir proteínas de calidad</li>
<li>Elegir grasas saludables</li>
<li>Consumir granos enteros</li>
<li>Limitar alimentos procesados</li>
</ul>',
                'metadata' => null,
            ],
        ];

        // GUÍAS
        $guias = [
            [
                'title' => 'Guía Completa para Principiantes en Nutrición',
                'type' => 'guia',
                'excerpt' => 'Todo lo que necesitas saber para empezar tu viaje hacia una alimentación más saludable.',
                'content' => '<h2>Introducción a la Nutrición Saludable</h2>
<p>Esta guía está diseñada para ayudarte a dar los primeros pasos hacia una alimentación más consciente y saludable.</p>

<h3>Los Macronutrientes</h3>

<h4>Proteínas</h4>
<p>Esenciales para construir y reparar tejidos. Necesitas aproximadamente 0.8-1g por kg de peso corporal al día.</p>
<p>Fuentes: pollo, pescado, huevos, legumbres, tofu, lácteos.</p>

<h4>Carbohidratos</h4>
<p>Principal fuente de energía del cuerpo. Prefiere carbohidratos complejos sobre simples.</p>
<p>Fuentes saludables: avena, arroz integral, quinoa, batata, frutas, verduras.</p>

<h4>Grasas</h4>
<p>Necesarias para absorber vitaminas y producir hormonas. Prioriza grasas insaturadas.</p>
<p>Fuentes: aguacate, nueces, aceite de oliva, pescado graso, semillas.</p>

<h3>Los Micronutrientes</h3>
<p>Vitaminas y minerales son esenciales en pequeñas cantidades. Una dieta variada generalmente cubre tus necesidades.</p>

<h3>Hidratación</h3>
<p>El agua es crucial para todas las funciones corporales. Bebe al menos 2 litros al día, más si haces ejercicio.</p>

<h3>Planificación de Comidas</h3>
<p>El método del plato:</p>
<ul>
<li>1/2 del plato: verduras y frutas</li>
<li>1/4 del plato: proteína</li>
<li>1/4 del plato: carbohidratos complejos</li>
<li>Una porción pequeña de grasas saludables</li>
</ul>

<h3>Consejos Prácticos</h3>
<ol>
<li>Come despacio y con atención</li>
<li>No te saltes el desayuno</li>
<li>Prepara tus comidas con anticipación</li>
<li>Lee las etiquetas nutricionales</li>
<li>Limita alimentos procesados</li>
<li>No te prohibas alimentos, busca balance</li>
<li>Consulta con un nutricionista profesional</li>
</ol>',
                'metadata' => null,
            ],
        ];

        // Insertar recetas
        foreach ($recetas as $data) {
            Resource::create([
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'type' => $data['type'],
                'excerpt' => $data['excerpt'],
                'content' => $data['content'],
                'metadata' => $data['metadata'],
                'author_id' => $author->id,
                'is_published' => true,
                'published_at' => now()->subDays(rand(1, 30)),
                'views' => rand(50, 500),
            ]);
        }

        // Insertar consejos
        foreach ($consejos as $data) {
            Resource::create([
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'type' => $data['type'],
                'excerpt' => $data['excerpt'],
                'content' => $data['content'],
                'metadata' => $data['metadata'],
                'author_id' => $author->id,
                'is_published' => true,
                'published_at' => now()->subDays(rand(1, 30)),
                'views' => rand(50, 500),
            ]);
        }

        // Insertar artículos
        foreach ($articulos as $data) {
            Resource::create([
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'type' => $data['type'],
                'excerpt' => $data['excerpt'],
                'content' => $data['content'],
                'metadata' => $data['metadata'],
                'author_id' => $author->id,
                'is_published' => true,
                'published_at' => now()->subDays(rand(1, 30)),
                'views' => rand(50, 500),
            ]);
        }

        // Insertar guías
        foreach ($guias as $data) {
            Resource::create([
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'type' => $data['type'],
                'excerpt' => $data['excerpt'],
                'content' => $data['content'],
                'metadata' => $data['metadata'],
                'author_id' => $author->id,
                'is_published' => true,
                'published_at' => now()->subDays(rand(1, 30)),
                'views' => rand(50, 500),
            ]);
        }

        $this->command->info('✅ Recursos creados exitosamente!');
        $this->command->info('   - ' . count($recetas) . ' recetas');
        $this->command->info('   - ' . count($consejos) . ' consejos');
        $this->command->info('   - ' . count($articulos) . ' artículos');
        $this->command->info('   - ' . count($guias) . ' guías');
    }
}
