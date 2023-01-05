import * as Yup from 'yup';

export const initialValues =
    {
        title: '',
        origin: '',
        category:'',
        person:'',
        ingredients:[
            {
                name : '',
                quantity :'',
                unit:''
            },
        ],
        steps:['']
    };
export const ValidationSchema = Yup.object().shape({
    title: Yup.string()
        .min(3, 'Le nom doit faire au moins 3 caractères')
        .max(150, 'Le nom ne doit pas faire plus de 50 caractères')
        .required('Veuillez saisir un nom de recette'),
    origin: Yup.string()
        .min(3, 'L\'origine doit faire au moins 3 caractères !')
        .max(50, 'L\'origine ne doit pas faire plus de 50 caractères')
        .required('Veuillez saisir une origine de recette'),
    category: Yup.string().required('Veuillez sélectionner une catégorie dans la liste'),
    person: Yup.number().required('Veuillez saisir le nombre de portions').max(10, 'Le nombre de portions ne peut dépasser 10 parts').test(
        'Is positive?',
        'ERROR: The number must be greater than 0!',
        (value) => value > 0
    ),
    ingredients: Yup.array().of(Yup.object().shape({
        name: Yup.string()
            .required('Veuillez saisir un nom d\'ingrédient'),
        quantity : Yup.number()
            .min(1,'La quantité doit être supérieure à 1')
            .max(10000,'La quantité ne doit pas être supérieur à 10000')
            .required('Veuillez saisir une quantité pour l\'ingrédient'),
        unit: Yup.string().required('Veuillez sélectionner une unité de mesure')
    })).min(2,'Au moins deux ingrédients sont nécessaires à l\'élaboration d\'une recette'),
    steps:Yup.array().of((Yup.string().required('Veuillez écrire l\'étape')))
});