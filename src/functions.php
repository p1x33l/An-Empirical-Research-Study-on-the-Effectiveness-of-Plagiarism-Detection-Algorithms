<?php
set_time_limit(5000);
include 'dbconnect.php';
include('php-nlp-tools-master/autoload.php');
include('php-ai/autoload.php');
include ('lemmatizer-master/vendor/autoload.php');
include ('php-lcs-develop/vendor/autoload.php');
include('php-stemmer-master/vendor/autoload.php');

use Eloquent\Lcs\LcsSolver;
use NlpTools\Tokenizers\WhitespaceAndPunctuationTokenizer;
use NlpTools\Stemmers\PorterStemmer;
use NlpTools\Similarity\JaccardIndex;
use NlpTools\Similarity\CosineSimilarity;
use NlpTools\Similarity\Simhash;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WhitespaceTokenizer; 
use writecrow\Lemmatizer\Lemmatizer;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Wamania\Snowball\StemmerFactory;

$pdo=new PDO("mysql:host=$db_hostname;dbname=$db_name;",$db_user,$db_password);
function ngrams($text,$n){
    $text=trim($text);
    $text_ngrams=array();
    $words=explode(" ",$text);
    if(count($words)>$n){
        
        for($i=0;$i<count($words)-$n+1;$i++){
            $ngrams="";
            for($j=0;$j<$n;$j++)
                $ngrams.=" ".$words[$i+$j];
            $text_ngrams[]=trim($ngrams);;
        }
    }
    else{
        $text_ngrams[]=$text;
    }
    return $text_ngrams;
}
function convert_arabic_numbers_to_english($string) {
    $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١','٠'];

    $num = range(0, 9);
    $convertedPersianNums = str_replace($persian, $num, $string);
    $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

    return $englishNumbersOnly;
}
function removeStopWordsEn($input){
    // Stop words list
    $stop_words=array("able","about","above","abroad","according","accordingly","across","actually","adj","after","afterwards","again","against","ago","ahead","ain't","all","allow","allows","almost","alone","along","alongside","already","also","although","always","am","amid","amidst","among","amongst","an","and","another","any","anybody","anyhow","anyone","anything","anyway","anyways","anywhere","apart","appear","appreciate","appropriate","are","aren't","around","as","a's","aside","ask","asking","associated","at","available","away","awfully","back","backward","backwards","be","became","because","become","becomes","becoming","been","before","beforehand","begin","behind","being","believe","below","beside","besides","best","better","between","beyond","both","brief","but","by","came","can","cannot","cant","can't","caption","cause","causes","certain","certainly","changes","clearly","c'mon","co","co.","com","come","comes","concerning","consequently","consider","considering","contain","containing","contains","corresponding","could","couldn't","course","c's","currently","dare","daren't","definitely","described","despite","did","didn't","different","directly","do","does","doesn't","doing","done","don't","down","downwards","during","each","edu","eg","eight","eighty","either","else","elsewhere","end","ending","enough","entirely","especially","et","etc","even","ever","evermore","every","everybody","everyone","everything","everywhere","ex","exactly","example","except","fairly","far","farther","few","fewer","fifth","first","five","followed","following","follows","for","forever","former","formerly","forth","forward","found","four","from","further","furthermore","get","gets","getting","given","gives","go","goes","going","gone","got","gotten","greetings","had","hadn't","half","happens","hardly","has","hasn't","have","haven't","having","he","he'd","he'll","hello","help","hence","her","here","hereafter","hereby","herein","here's","hereupon","hers","herself","he's","hi","him","himself","his","hither","hopefully","how","howbeit","however","hundred","i'd","ie","if","ignored","i'll","i'm","immediate","in","inasmuch","inc","inc.","indeed","indicate","indicated","indicates","inner","inside","insofar","instead","into","inward","is","isn't","it","it'd","it'll","its","it's","itself","i've","just","k","keep","keeps","kept","know","known","knows","last","lately","later","latter","latterly","least","less","lest","let","let's","like","liked","likely","likewise","little","look","looking","looks","low","lower","ltd","made","mainly","make","makes","many","may","maybe","mayn't","me","mean","meantime","meanwhile","merely","might","mightn't","mine","minus","miss","more","moreover","most","mostly","mr","mrs","much","must","mustn't","my","myself","name","namely","nd","near","nearly","necessary","need","needn't","needs","neither","never","neverf","neverless","nevertheless","new","next","nine","ninety","no","nobody","non","none","nonetheless","noone","no-one","nor","normally","not","nothing","notwithstanding","novel","now","nowhere","obviously","of","off","often","oh","ok","okay","old","on","once","one","ones","one's","only","onto","opposite","or","other","others","otherwise","ought","oughtn't","our","ours","ourselves","out","outside","over","overall","own","particular","particularly","past","per","perhaps","placed","please","plus","possible","presumably","probably","provided","provides","que","quite","qv","rather","rd","re","really","reasonably","recent","recently","regarding","regardless","regards","relatively","respectively","right","round","said","same","saw","say","saying","says","second","secondly","see","seeing","seem","seemed","seeming","seems","seen","self","selves","sensible","sent","serious","seriously","seven","several","shall","shan't","she","she'd","she'll","she's","should","shouldn't","since","six","so","some","somebody","someday","somehow","someone","something","sometime","sometimes","somewhat","somewhere","soon","sorry","specified","specify","specifying","still","sub","such","sup","sure","take","taken","taking","tell","tends","th","than","thank","thanks","thanx","that","that'll","thats","that's","that've","the","their","theirs","them","themselves","then","thence","there","thereafter","thereby","there'd","therefore","therein","there'll","there're","theres","there's","thereupon","there've","these","they","they'd","they'll","they're","they've","thing","things","think","third","thirty","this","thorough","thoroughly","those","though","three","through","throughout","thru","thus","till","to","together","too","took","toward","towards","tried","tries","truly","try","trying","t's","twice","two","un","under","underneath","undoing","unfortunately","unless","unlike","unlikely","until","unto","up","upon","upwards","us","use","used","useful","uses","using","usually","v","value","various","versus","very","via","viz","vs","want","wants","was","wasn't","way","we","we'd","welcome","well","we'll","went","were","we're","weren't","we've","what","whatever","what'll","what's","what've","when","whence","whenever","where","whereafter","whereas","whereby","wherein","where's","whereupon","wherever","whether","which","whichever","while","whilst","whither","who","who'd","whoever","whole","who'll","whom","whomever","who's","whose","why","will","willing","wish","with","within","without","wonder","won't","would","wouldn't","yes","yet","you","you'd","you'll","your","you're","yours","yourself","yourselves","you've","zero","a","how's","i","when's","why's","b","c","d","e","f","g","h","j","l","m","n","o","p","q","r","s","t","u","uucp","w","x","y","z","I","www","amount","bill","bottom","call","computer","con","couldnt","cry","de","describe","detail","due","eleven","empty","fifteen","fifty","fill","find","fire","forty","front","full","give","hasnt","herse","himse","interest","itse”","mill","move","myse”","part","put","show","side","sincere","sixty","system","ten","thick","thin","top","twelve","twenty","abst","accordance","act","added","adopted","affected","affecting","affects","ah","announce","anymore","apparently","approximately","aren","arent","arise","auth","beginning","beginnings","begins","biol","briefly","ca","date","ed","effect","et-al","ff","fix","gave","giving","heres","hes","hid","home","id","im","immediately","importance","important","index","information","invention","itd","keys","kg","km","largely","lets","line","'ll","means","mg","million","ml","mug","na","nay","necessarily","nos","noted","obtain","obtained","omitted","ord","owing","page","pages","poorly","possibly","potentially","pp","predominantly","present","previously","primarily","promptly","proud","quickly","ran","readily","ref","refs","related","research","resulted","resulting","results","run","sec","section","shed","shes","showed","shown","showns","shows","significant","significantly","similar","similarly","slightly","somethan","specifically","state","states","stop","strongly","substantially","successfully","sufficiently","suggest","thered","thereof","therere","thereto","theyd","theyre","thou","thoughh","thousand","throug","til","tip","ts","ups","usefully","usefulness","'ve","vol","vols","wed","whats","wheres","whim","whod","whos","widely","words","world","youd","youre");
    return preg_replace('/\b('.implode('|',$stop_words).')\b/','',$input);
}
function removeStopWordsFr($input){
    $input=strtolower($input);
    $stop_words=array('a','A','à','afin','ah','ai','aie','aient','aies','ailleurs','ainsi','ait','alentour','alias','allais','allaient','allait','allons','allez','alors','Ap.','Apr.','après','après-demain','arrière','as','assez','attendu','au','aucun','aucune','au-dedans','au-dehors','au-delà','au-dessous','au-dessus','au-devant','audit','aujourd\'','aujourd\'hui','auparavant','auprès','auquel','aura','aurai','auraient','aurais','aurait','auras','aurez','auriez','aurions','aurons','auront','aussi','aussitôt','autant','autour','autre','autrefois','autres','autrui','aux','auxdites','auxdits','auxquelles','auxquels','avaient','avais','avait','avant','avant-hier','avec','avez','aviez','avions','avoir','avons','ayant','ayez','ayons','B','bah','banco','bé','beaucoup','ben','bien','bientôt','bis','bon','C','c\'','ç\'','c.-à-d.','Ca','ça','çà','cahin-caha','car','ce','-ce','céans','ceci','cela','celle','celle-ci','celle-là','celles','celles-ci','celles-là','celui','celui-ci','celui-là','cent','cents','cependant','certain','certaine','certaines','certains','certes','ces','c\'est-à-dire','cet','cette','ceux','ceux-ci','ceux-là','cf.','cg','cgr','chacun','chacune','chaque','cher','chez','ci','-ci','ci-après','ci-dessous','ci-dessus','cinq','cinquante','cinquante-cinq','cinquante-deux','cinquante-et-un','cinquante-huit','cinquante-neuf','cinquante-quatre','cinquante-sept','cinquante-six','cinquante-trois','cl','cm','cm²','combien','comme','comment','contrario','contre','crescendo','D','d\'','l\'','d\'abord','d\'accord','d\'affilée','d\'ailleurs','dans','d\'après','d\'arrache-pied','davantage','de','debout','dedans','dehors','déjà','delà','demain','d\'emblée','depuis','derechef','derrière','des','dès','desdites','desdits','désormais','desquelles','desquels','dessous','dessus','deux','devant','devers','dg','die','différentes','différents','dire','dis','disent','dit','dito','divers','diverses','dix','dix-huit','dix-neuf','dix-sept','dl','dm','donc','dont','dorénavant','douze','du','dû','dudit','duquel','durant','E','eh','elle','-elle','elles','-elles','en','\'en','-en','encore','enfin','ensemble','ensuite','entre','entre-temps','envers','environ','es','ès','est','et','et\/ou','étaient','étais','était','étant','etc','été','êtes','étiez','étions','être','eu','eue','eues','euh','eûmes','eurent','eus','eusse','eussent','eusses','eussiez','eussions','eut','eût','eûtes','eux','exprès','extenso','extremis','F','facto','fallait','faire','fais','faisais','faisait','faisaient','faisons','fait','faites','faudrait','faut','fi','flac','fors','fort','forte','fortiori','frais','fûmes','fur','furent','fus','fusse','fussent','fusses','fussiez','fussions','fut','fût','fûtes','G','gr','grosso','guère','H','ha','han','haut','hé','hein','hem','heu','hg','hier','hl','holà','hop','hormis','hors','hui','huit','hum','I','ibidem','ici','ici-bas','idem','il','-il','illico','ils','-ils','ipso','item','J','j\'','jadis','jamais','je','-je','jusqu\'','jusqu\'à','jusqu\'au','jusqu\'aux','jusque','juste','l\'','la','-la','là','-là','là-bas','là-dedans','là-dehors','là-derrière','là-dessous','là-dessus','là-devant','là-haut','laquelle','l\'autre','le','-le','lequel','les','-les','lès','lesquelles','lesquels','leur','-leur','leurs','lez','loin','l\'on','longtemps','lors','lorsqu\'','lorsque','lui','-lui','l\'un','l\'une','M','m\'','ma','maint','mainte','maintenant','maintes','maints','mais','mal','malgré','me','même','mêmes','mes','mg','mgr','mieux','mil','mille','milliards','millions','minima','modo','moi','-moi','moins','mon','moult','moyennant','N','n\'','naguère','ne','néanmoins','neuf','ni','non','nonante','nonobstant','nos','notre','nous','-nous','nul','nulle','O','ô','octante','oh','on','-on','ont','onze','or','ou','où','ouais','oui','outre','P','par','parbleu','parce','par-ci','par-delà','par-derrière','par-dessous','par-dessus','par-devant','parfois','par-là','parmi','partout','pas','passé','passim','pendant','personne','petto','peu','peut','peuvent','peux','peut-être','pis','plus','plusieurs','plutôt','point','posteriori','pour','pourquoi','pourtant','préalable','près','presqu\'','presque','primo','priori','prou','pu','puis','puisqu\'','puisque','Q','qu\'','qua','quand','quarante','quarante-cinq','quarante-deux','quarante-et-un','quarante-huit','quarante-neuf','quarante-quatre','quarante-sept','quarante-six','quarante-trois','quasi','quatorze','quatre','quatre-vingt','quatre-vingt-cinq','quatre-vingt-deux','quatre-vingt-dix','quatre-vingt-dix-huit','quatre-vingt-dix-neuf','quatre-vingt-dix-sept','quatre-vingt-douze','quatre-vingt-huit','quatre-vingt-neuf','quatre-vingt-onze','quatre-vingt-quatorze','quatre-vingt-quatre','quatre-vingt-quinze','quatre-vingts','quatre-vingt-seize','quatre-vingt-sept','quatre-vingt-six','quatre-vingt-treize','quatre-vingt-trois','quatre-vingt-un','quatre-vingt-une','que','quel','quelle','quelles','quelqu\'','quelque','quelquefois','quelques','quelques-unes','quelques-uns','quelqu\'un','quelqu\'une','quels','qui','quiconque','quinze','quoi','quoiqu\'','quoique','R','revoici','revoilà','rien','S','s\'','sa','sans','sauf','se','secundo','seize','selon','sensu','sept','septante','sera','serai','seraient','serais','serait','seras','serez','seriez','serions','serons','seront','ses','si','sic','sine','sinon','sitôt','situ','six','soi','soient','sois','soit','soixante','soixante-cinq','soixante-deux','soixante-dix','soixante-dix-huit','soixante-dix-neuf','soixante-dix-sept','soixante-douze','soixante-et-onze','soixante-et-un','soixante-et-une','soixante-huit','soixante-neuf','soixante-quatorze','soixante-quatre','soixante-quinze','soixante-seize','soixante-sept','soixante-six','soixante-treize','soixante-trois','sommes','son','sont','soudain','sous','souvent','soyez','soyons','stricto','suis','sur','sur-le-champ','surtout','sus','T','-t','t\'','ta','tacatac','tant','tantôt','tard','te','tel','telle','telles','tels','ter','tes','toi','-toi','ton','tôt','toujours','tous','tout','toute','toutefois','toutes','treize','trente','trente-cinq','trente-deux','trente-et-un','trente-huit','trente-neuf','trente-quatre','trente-sept','trente-six','trente-trois','très','trois','trop','tu','-tu','U','un','une','unes','uns','USD','V','va','vais','vas','vers','veut','veux','via','vice-versa','vingt','vingt-cinq','vingt-deux','vingt-huit','vingt-neuf','vingt-quatre','vingt-sept','vingt-six','vingt-trois','vis-à-vis','vite','vitro','vivo','voici','voilà','voire','volontiers','vos','votre','vous','-vous','W','X','y','-y','Z','zéro');
    $text_nostopword = preg_replace('/\b('.implode('|',$stop_words).')\b/','',$input); 
    $result = array_diff(ngrams($text_nostopword ,1),$stop_words);
    return implode(" ",$result);
}
function removeStopWordsAr($input){
    $input=strtolower($input);
    $stop_words=array('،', 'ء', 'ءَ', 'آ', 'آب', 'آذار', 'آض', 'آل', 'آمينَ', 'آناء', 'آنفا', 'آه', 'آهاً', 'آهٍ', 'آهِ', 'أ', 'أبدا', 'أبريل', 'أبو', 'أبٌ', 'أجل', 'أجمع', 'أحد', 'أخبر', 'أخذ', 'أخو', 'أخٌ', 'أربع', 'أربعاء', 'أربعة', 'أربعمئة', 'أربعمائة', 'أرى', 'أسكن', 'أصبح', 'أصلا', 'أضحى', 'أطعم', 'أعطى', 'أعلم', 'أغسطس', 'أفريل', 'أفعل', 'به', 'أفٍّ', 'أقبل', 'أكتوبر', 'أل', 'ألا', 'ألف', 'ألفى', 'أم', 'أما', 'أمام', 'أمامك', 'أمامكَ', 'أمد', 'أمس', 'أمسى', 'أمّا', 'أن', 'أنا', 'أنبأ', 'أنت', 'أنتم', 'أنتما', 'أنتن', 'أنتِ', 'أنشأ', 'أنه', 'أنًّ', 'أنّى', 'أهلا', 'أو', 'أوت', 'أوشك', 'أول', 'أولئك', 'أولاء', 'أولالك', 'أوّهْ', 'أى', 'أي', 'أيا', 'أيار', 'أيضا', 'أيلول', 'أين', 'أيّ', 'أيّان', 'أُفٍّ', 'ؤ', 'إحدى', 'إذ', 'إذا', 'إذاً', 'إذما', 'إذن', 'إزاء', 'إلى', 'إلي', 'إليكم', 'إليكما', 'إليكنّ', 'إليكَ', 'إلَيْكَ', 'إلّا', 'إمّا', 'إن', 'إنَّ', 'إى', 'إياك', 'إياكم', 'إياكما', 'إياكن', 'إيانا', 'إياه', 'إياها', 'إياهم', 'إياهما', 'إياهن', 'إياي', 'إيهٍ', 'ئ', 'ا', 'ا?', 'ا?ى', 'االا', 'االتى', 'ابتدأ', 'ابين', 'اتخذ', 'اثر', 'اثنا', 'اثنان', 'اثني', 'اثنين', 'اجل', 'احد', 'اخرى', 'اخلولق', 'اذا', 'اربعة', 'اربعون', 'اربعين', 'ارتدّ', 'استحال', 'اصبح', 'اضحى', 'اطار', 'اعادة', 'اعلنت', 'اف', 'اكثر', 'اكد', 'الآن', 'الألاء', 'الألى', 'الا', 'الاخيرة', 'الان', 'الاول', 'الاولى', 'التى', 'التي', 'الثاني', 'الثانية', 'الحالي', 'الذاتي', 'الذى', 'الذي', 'الذين', 'السابق', 'الف', 'اللاتي', 'اللتان', 'اللتيا', 'اللتين', 'اللذان', 'اللذين', 'اللواتي', 'الماضي', 'المقبل', 'الوقت', 'الى', 'الي', 'اليه', 'اليها', 'اليوم', 'اما', 'امام', 'امس', 'امسى', 'ان', 'انبرى', 'انقلب', 'انه', 'انها', 'او', 'اول', 'اي', 'ايار', 'ايام', 'ايضا', 'ب', 'بؤسا', 'بإن', 'بئس', 'باء', 'بات', 'باسم', 'بان', 'بخٍ', 'بد', 'بدلا', 'برس', 'بسبب', 'بسّ', 'بشكل', 'بضع', 'بطآن', 'بعد', 'بعدا', 'بعض', 'بغتة', 'بل', 'بلى', 'بن', 'به', 'بها', 'بهذا', 'بيد', 'بين', 'بَسْ', 'بَلْهَ', 'ة', 'ت', 'تاء', 'تارة', 'تاسع', 'تانِ', 'تانِك', 'تبدّل', 'تجاه', 'تحت', 'تحوّل', 'تخذ', 'ترك', 'تسع', 'تسعة', 'تسعمئة', 'تسعمائة', 'تسعون', 'تسعين', 'تشرين', 'تعسا', 'تعلَّم', 'تفعلان', 'تفعلون', 'تفعلين', 'تكون', 'تلقاء', 'تلك', 'تم', 'تموز', 'تينك', 'تَيْنِ', 'تِه', 'تِي', 'ث', 'ثاء', 'ثالث', 'ثامن', 'ثان', 'ثاني', 'ثلاث', 'ثلاثاء', 'ثلاثة', 'ثلاثمئة', 'ثلاثمائة', 'ثلاثون', 'ثلاثين', 'ثم', 'ثمان', 'ثمانمئة', 'ثمانون', 'ثماني', 'ثمانية', 'ثمانين', 'ثمنمئة', 'ثمَّ', 'ثمّ', 'ثمّة', 'ج', 'جانفي', 'جدا', 'جعل', 'جلل', 'جمعة', 'جميع', 'جنيه', 'جوان', 'جويلية', 'جير', 'جيم', 'ح', 'حاء', 'حادي', 'حار', 'حاشا', 'حاليا', 'حاي', 'حبذا', 'حبيب', 'حتى', 'حجا', 'حدَث', 'حرى', 'حزيران', 'حسب', 'حقا', 'حمدا', 'حمو', 'حمٌ', 'حوالى', 'حول', 'حيث', 'حيثما', 'حين', 'حيَّ', 'حَذارِ', 'خ', 'خاء', 'خاصة', 'خال', 'خامس', 'خبَّر', 'خلا', 'خلافا', 'خلال', 'خلف', 'خمس', 'خمسة', 'خمسمئة', 'خمسمائة', 'خمسون', 'خمسين', 'خميس', 'د', 'دال', 'درهم', 'درى', 'دواليك', 'دولار', 'دون', 'دونك', 'ديسمبر', 'دينار', 'ذ', 'ذا', 'ذات', 'ذاك', 'ذال', 'ذانك', 'ذانِ', 'ذلك', 'ذهب', 'ذو', 'ذيت', 'ذينك', 'ذَيْنِ', 'ذِه', 'ذِي', 'ر', 'رأى', 'راء', 'رابع', 'راح', 'رجع', 'رزق', 'رويدك', 'ريال', 'ريث', 'رُبَّ', 'ز', 'زاي', 'زعم', 'زود', 'زيارة', 'س', 'ساء', 'سابع', 'سادس', 'سبت', 'سبتمبر', 'سبحان', 'سبع', 'سبعة', 'سبعمئة', 'سبعمائة', 'سبعون', 'سبعين', 'ست', 'ستة', 'ستكون', 'ستمئة', 'ستمائة', 'ستون', 'ستين', 'سحقا', 'سرا', 'سرعان', 'سقى', 'سمعا', 'سنة', 'سنتيم', 'سنوات', 'سوف', 'سوى', 'سين', 'ش', 'شباط', 'شبه', 'شتانَ', 'شخصا', 'شرع', 'شمال', 'شيكل', 'شين', 'شَتَّانَ', 'ص', 'صاد', 'صار', 'صباح', 'صبر', 'صبرا', 'صدقا', 'صراحة', 'صفر', 'صهٍ', 'صهْ', 'ض', 'ضاد', 'ضحوة', 'ضد', 'ضمن', 'ط', 'طاء', 'طاق', 'طالما', 'طرا', 'طفق', 'طَق', 'ظ', 'ظاء', 'ظل', 'ظلّ', 'ظنَّ', 'ع', 'عاد', 'عاشر', 'عام', 'عاما', 'عامة', 'عجبا', 'عدا', 'عدة', 'عدد', 'عدم', 'عدَّ', 'عسى', 'عشر', 'عشرة', 'عشرون', 'عشرين', 'عل', 'علق', 'علم', 'على', 'علي', 'عليك', 'عليه', 'عليها', 'علًّ', 'عن', 'عند', 'عندما', 'عنه', 'عنها', 'عوض', 'عيانا', 'عين', 'عَدَسْ', 'غ', 'غادر', 'غالبا', 'غدا', 'غداة', 'غير', 'غين', 'ـ', 'ف', 'فإن', 'فاء', 'فان', 'فانه', 'فبراير', 'فرادى', 'فضلا', 'فقد', 'فقط', 'فكان', 'فلان', 'فلس', 'فهو', 'فو', 'فوق', 'فى', 'في', 'فيفري', 'فيه', 'فيها', 'ق', 'قاطبة', 'قاف', 'قال', 'قام', 'قبل', 'قد', 'قرش', 'قطّ', 'قلما', 'قوة', 'ك', 'كأن', 'كأنّ', 'كأيّ', 'كأيّن', 'كاد', 'كاف', 'كان', 'كانت', 'كانون', 'كثيرا', 'كذا', 'كذلك', 'كرب', 'كسا', 'كل', 'كلتا', 'كلم', 'كلَّا', 'كلّما', 'كم', 'كما', 'كن', 'كى', 'كيت', 'كيف', 'كيفما', 'كِخ', 'ل', 'لأن', 'لا', 'لا', 'سيما', 'لات', 'لازال', 'لاسيما', 'لام', 'لايزال', 'لبيك', 'لدن', 'لدى', 'لدي', 'لذلك', 'لعل', 'لعلَّ', 'لعمر', 'لقاء', 'لكن', 'لكنه', 'لكنَّ', 'للامم', 'لم', 'لما', 'لمّا', 'لن', 'له', 'لها', 'لهذا', 'لهم', 'لو', 'لوكالة', 'لولا', 'لوما', 'ليت', 'ليرة', 'ليس', 'ليسب', 'م', 'مئة', 'مئتان', 'ما', 'ما', 'أفعله', 'ما', 'انفك', 'ما', 'برح', 'مائة', 'ماانفك', 'مابرح', 'مادام', 'ماذا', 'مارس', 'مازال', 'مافتئ', 'ماي', 'مايزال', 'مايو', 'متى', 'مثل', 'مذ', 'مرّة', 'مساء', 'مع', 'معاذ', 'معه', 'معها', 'مقابل', 'مكانكم', 'مكانكما', 'مكانكنّ', 'مكانَك', 'مليار', 'مليم', 'مليون', 'مما', 'من', 'منذ', 'منه', 'منها', 'مه', 'مهما', 'ميم', 'ن', 'نا', 'نبَّا', 'نحن', 'نحو', 'نعم', 'نفس', 'نفسه', 'نهاية', 'نوفمبر', 'نون', 'نيسان', 'نيف', 'نَخْ', 'نَّ', 'ه', 'هؤلاء', 'ها', 'هاء', 'هاكَ', 'هبّ', 'هذا', 'هذه', 'هل', 'هللة', 'هلم', 'هلّا', 'هم', 'هما', 'همزة', 'هن', 'هنا', 'هناك', 'هنالك', 'هو', 'هي', 'هيا', 'هيهات', 'هيّا', 'هَؤلاء', 'هَاتانِ', 'هَاتَيْنِ', 'هَاتِه', 'هَاتِي', 'هَجْ', 'هَذا', 'هَذانِ', 'هَذَيْنِ', 'هَذِه', 'هَذِي', 'هَيْهات', 'و', 'و6', 'وأبو', 'وأن', 'وا', 'واحد', 'واضاف', 'واضافت', 'واكد', 'والتي', 'والذي', 'وان', 'واهاً', 'واو', 'واوضح', 'وبين', 'وثي', 'وجد', 'وراءَك', 'ورد', 'وعلى', 'وفي', 'وقال', 'وقالت', 'وقد', 'وقف', 'وكان', 'وكانت', 'ولا', 'ولايزال', 'ولكن', 'ولم', 'وله', 'وليس', 'ومع', 'ومن', 'وهب', 'وهذا', 'وهو', 'وهي', 'وَيْ', 'وُشْكَانَ', 'ى', 'ي', 'ياء', 'يفعلان', 'يفعلون', 'يكون', 'يلي', 'يمكن', 'يمين', 'ين', 'يناير', 'يوان', 'يورو', 'يوليو', 'يوم', 'يونيو', 'ّأيّان');
    $text = "";
    $resutl = array_diff(ngrams($input,1),$stop_words);
    foreach($resutl as $word){
        $text .=$word." ";
    }
    return $text;
}
function stemmer_fr($input){
    $text_stemming = "";
    $unigram = ngrams($input,1);
    $stemmer = StemmerFactory::create('fr');
    foreach($unigram as $word){
        $word2=$stemmer->stem($word);
        $text_stemming .=$word2.' ';
    }
    return $text_stemming;
}

function pretraitement_ngrams($text,$n,$lang){
    $tok = new WhitespaceAndPunctuationTokenizer();
    $stemmer = new PorterStemmer();

    $text=strtolower($text);

    $text_array=$tok->tokenize($text);
    
    for($i=0;$i<count($text_array);$i++){
        if($lang=='en'){
            $text_array[$i]=preg_replace('/\p{P}/', '', $text_array[$i]);
            $text_array[$i]=preg_replace('/[0-9]+/', '', $text_array[$i]);
            if($text_array[$i]=='')
                continue;
            $text_array[$i]=Lemmatizer::getLemma($text_array[$i]);
            $text_array[$i]=$stemmer->stem($text_array[$i]);
            $text_array[$i]=trim(removeStopWordsEn($text_array[$i]));
        }
        if($lang=='fr'){
            $text_array[$i]=str_replace(['\'','_','’',',',';',':','?', '!', '.','«','»','-','- -','”','“','«','»',' "','"','...','{','}','(',')','[',']','+','-','*','/','&','|','<','>','=','~','@','$','\\'], '', $text_array[$i]);
            $text_array[$i]=preg_replace('/[0-9]+/', '', $text_array[$i]);
            $text_array[$i]=stemmer_fr($text_array[$i]);
            $text_array[$i]=trim(removeStopWordsFr($text_array[$i]));
        }
        if($lang=='ar'){
            $text_array[$i]=convert_arabic_numbers_to_english($text_array[$i]);
            $text_array[$i]=preg_replace('/[0-9]+/', '', $text_array[$i]);
            $text_array[$i]=str_replace(['،','؛','؟','!', '.', ':','-','- -','”','“','«','»',' "','"','...','{','}','(',')','[',']','+','-','*','/','&','|','<','>','=','~','@','$','\\','/'], '', $text_array[$i]);
            $pnct_ar = array('ِ', 'ُ', 'ٓ', 'ٰ', 'ْ', 'ٌ', 'ٍ', 'ً', 'ّ', 'َ');
            $text_array[$i] = str_replace($pnct_ar, '', $text_array[$i]);
            $text_array[$i]=trim(removeStopWordsAr($text_array[$i]));
        }
    }
    $text_array = array_map('trim', $text_array);
    $text=trim(implode(' ',$text_array));
    $text_ngrams=ngrams($text,$n);
    $text_ngrams=array_filter($text_ngrams);
    $text_ngrams= array_map('trim', $text_ngrams);
    return $text_ngrams;
}

function algorithm($test_array,$source_array){
    foreach ($test_array as $test_sentence){
        $max_source_sentence="";
        $max_plagiat=0;
        foreach($source_array as $source_sentence){
            
            //strcmp
            // if(strcmp($test_sentence,$source_sentence)==0)
            //     $plagiat=100;
            // else
            //     $plagiat=0;
            

            //similar_Text
            // similar_text($test_sentence,$source_sentence,$plagiat);
            
            
            //levenshtein
            $plagiat=100-levenshtein($test_sentence,$source_sentence)*100/strlen($test_sentence);
            
            //DiceMatch
            //$plagiat=DiceMatch($test_sentence, $source_sentence)*100;
            
            
            //pretraitement for cosine/jacard/simhash
            /*$tok = new WhitespaceAndPunctuationTokenizer();
            $setA = $tok->tokenize($test_sentence);
            $setB = $tok->tokenize($source_sentence);*/
            
            
            //Jacard
            /*$J = new JaccardIndex();
            $plagiat=$J->similarity($setA,$setB)*100;*/
            
            

            //Cosine
            /*$cos = new CosineSimilarity();
            $plagiat=$cos->similarity($setA,$setB)*100;*/
          
            /*
            //SimHash
            $simhash = new Simhash(16); //16 bits hash
            $plagiat = $simhash->similarity($setA,$setB)*100;
            */

            //Longest common subsequence
            
            
            /*$solver = new LcsSolver();
            $sequenceA = str_split($test_sentence);
            $sequenceB = str_split($source_sentence);
            $lcs = $solver->longestCommonSubsequence($sequenceA, $sequenceB);
            $plagiat=count($lcs)*100/((strlen($test_sentence)+strlen($source_sentence))/2);*/
            


            if($plagiat>$max_plagiat){
                $max_plagiat=$plagiat;
                $max_source_sentence=$source_sentence;
            }   
        }
        /*if($max_plagiat>50){
            $max_plagiat=100;
        }else{
            $max_plagiat=0;
        }*/
        $results[]=array($test_sentence,$max_source_sentence,number_format($max_plagiat,2));
    }
    return $results;  
}

function percentage($results){
    $total = array_column($results , 2);
    $percent=number_format(array_sum($total)/count($total), 2);    
    return $percent;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function getArticlesData($lang){
    global $pdo;
    $ins=$pdo->prepare('select * from articles41 where lang=?');
    $ins->execute(array($lang));
    $data=$ins->fetchall();
    return $data;    
}
function getFileContent($path){
    $myfile = fopen($path, "r") or die("Unable to open file!");
    $data=fread($myfile,filesize($path));
    fclose($myfile);
    return $data;
}
function sortByScore($tab){
    $score = array_column($tab, 'score');
    array_multisort($score, SORT_DESC, $tab);
    return $tab;
}

function getUploadedFileContent($inputFile){
    $target_dir = "FilesLog/";
    $target_file = $target_dir . basename($inputFile["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check file size
    if ($inputFile["size"] > 10000000) {
        echo "<script>alert('Sorry, your file is too large.')</script>";
        $uploadOk = 0;
    }
    // Allow txt file formats
    if($fileType != "txt") {
    echo "<script>alert('Sorry, only TXT files are allowed.')</script>";
    $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
    echo "<script>alert('Sorry, your file was not uploaded.')</script>";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($inputFile["tmp_name"], $target_file)) {
            $content=getFileContent($target_file);
            unlink($target_file);
            return $content;
        } else {
            echo "Sorry, there was an error uploading your file.";
            return "";
        }
    }
}

function tfidfCosinePreProcessing($text,$lang){
    $tok = new WhitespaceAndPunctuationTokenizer();
    $stemmer = new PorterStemmer();

    $text=strtolower($text);
    
    $text_array=$tok->tokenize($text);

    for($i=0;$i<count($text_array);$i++){
        if($lang=='en'){
            $text_array[$i]=preg_replace('/\p{P}/', '', $text_array[$i]);
            $text_array[$i]=preg_replace('/[0-9]+/', '', $text_array[$i]);
            if($text_array[$i]=='')
                continue;
            $text_array[$i]=Lemmatizer::getLemma($text_array[$i]);
            $text_array[$i]=$stemmer->stem($text_array[$i]);
            $text_array[$i]=trim(removeStopWordsEn($text_array[$i]));
        }
        if($lang=='fr'){
            $text_array[$i]=str_replace(['\'','_','’',',',';',':','?', '!', '.','«','»','-','- -','”','“','«','»',' "','"','...','{','}','(',')','[',']','+','-','*','/','&','|','<','>','=','~','@','$','\\'], '', $text_array[$i]);
            $text_array[$i]=preg_replace('/[0-9]+/', '', $text_array[$i]);
            $text_array[$i]=stemmer_fr($text_array[$i]);
            $text_array[$i]=trim(removeStopWordsFr($text_array[$i]));
        }
        if($lang=='ar'){
            $text_array[$i]=convert_arabic_numbers_to_english($text_array[$i]);
            $text_array[$i]=preg_replace('/[0-9]+/', '', $text_array[$i]);
            $text_array[$i]=str_replace(['،','؛','؟','!', '.', ':','-','- -','”','“','«','»',' "','"','...','{','}','(',')','[',']','+','-','*','/','&','|','<','>','=','~','@','$','\\','/'], '', $text_array[$i]);
            $pnct_ar = array('ِ', 'ُ', 'ٓ', 'ٰ', 'ْ', 'ٌ', 'ٍ', 'ً', 'ّ', 'َ');
            $text_array[$i] = str_replace($pnct_ar, '', $text_array[$i]);
            $text_array[$i]=trim(removeStopWordsAr($text_array[$i]));
        }
    }    
    
    $text_array = array_map('trim', $text_array);
    $text=trim(implode(' ',$text_array));

    return $text;
}

function tfidfCosine($test,$source,$lang){
    $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());
    $cos = new CosineSimilarity();

    $source=tfidfCosinePreProcessing($source,$lang);
    $test=tfidfCosinePreProcessing($test,$lang);

    $vector_source=array($source);
    $vector_test=array($test);
    
    $vectorizer->fit($vector_test); //dictionary
    
    $vectorizer->transform($vector_source);
    $vectorizer->transform($vector_test);

    //---------Term Occurence-----------
    $vector_test=$vector_test[0];
    $vector_source=$vector_source[0];

    //---------TF-IDF-----Delete comments to activate it------
    /*$vectors=[$vector_source,$vector_test];
    $transformer = new TfIdfTransformer($vectors);
    $transformer->transform($vectors);
    for($i=0;$i<count($vectors[0]);$i++){
        $vectors[0][$i]=(int)($vectors[0][$i]*1000);
        $vectors[1][$i]=(int)($vectors[1][$i]*1000);
    }
    $vector_source=$vectors[0];
    $vector_test=$vectors[1];*/
    

    $score=$cos->similarity($vector_test,$vector_source);
    return $score;
}
function DiceMatch($string1, $string2)
{
	if (empty($string1) || empty($string2))
		return 0;

	if ($string1 == $string2)
		return 1;

	$strlen1 = strlen($string1);
	$strlen2 = strlen($string2);

	if ($strlen1 < 2 || $strlen2 < 2)
		return 0;

	$length1 = $strlen1 - 1;
	$length2 = $strlen2 - 1;

	$matches = 0;
	$i = 0;
	$j = 0;

	while ($i < $length1 && $j < $length2)
	{
		$a = substr($string1, $i, 2);
		$b = substr($string2, $j, 2);
		$cmp = strcasecmp($a, $b);

		if ($cmp == 0)
			$matches += 2;

		++$i;
		++$j;
	}

	return $matches / ($length1 + $length2);
}
?>
