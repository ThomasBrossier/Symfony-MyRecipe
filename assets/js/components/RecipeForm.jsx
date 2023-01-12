import React, {useEffect, useState} from 'react';
import ReactDOM from 'react-dom/client';
import {ErrorMessage, Field, FieldArray, Form, Formik} from 'formik';
import IngredientForm from "./IngredientForm";
import {Box, Card, Input, InputLabel, MenuItem, TextField} from "@mui/material";
import {initialValues, ValidationSchema} from "./ValidationForm";
import StepForm from "./stepForm";

const RecipeForm = () => {
    const [categories, setCategories] = useState([]);
    const [currentPictureName, setCurrentPictureName] = useState( )
    const [currentPicture, setCurrentPicture] = useState( )
    useEffect(()=>{
        fetch('https://127.0.0.1:8000/api/recipe/categories')
            .then(res=> res.json())
            .then(data => setCategories(data))
            .catch(err=> console.error(err));
    },[])
    const UploadFile = (event, setFieldValue) => {
        if (!event.target.files?.length) return;
        const fileReader = new FileReader();
        const file = event.target.files[0];
        fileReader.readAsDataURL(file);
        fileReader.onloadend = () => {
            const content = fileReader.result;
            if (content) {
                setCurrentPictureName(content);
            }
            setFieldValue('picture', file)
        }};
    return (
        <Formik
            onSubmit={( values , {resetForm}) => {
                /* Then create a new FormData obj */
                let formData = new FormData();
                console.log(values['picture'])
                /* append input field values to formData */
                for (let value in values) {
                    formData.append(value, JSON.stringify(values[value]) );
                }
                formData.append('picture',values['picture'])
                let params = {
                    body : formData,
                    mode: 'same-origin',
                    method:'POST',
                }
                fetch('https://127.0.0.1:8000/api/recipe/new',params)
                    .then(res => res.json())
                    .then(()=> resetForm(initialValues))
            }}
            initialValues={initialValues}
            validationSchema={ ValidationSchema }
            validateOnChange={false}
            >
            {({values,isValid,errors, setFieldValue,...props})=>(
                <Form className="d-flex flex-column">
                    <Card className="p-3 mb-3">
                        <div className="mb-3 form-group">
                            <Field
                                type="text"
                                as={TextField}
                                helperText={<ErrorMessage name="title"/>}
                                error={errors.title && props.touched.title}
                                label="Nom de la recette"
                                name="title"
                                fullWidth
                            />

                        </div>
                        <div className="mb-3 form-group">
                            <Field
                                type="text"
                                as={TextField}
                                helperText={<ErrorMessage name="origin"/>}
                                error={errors.origin && props.touched.origin}
                                name="origin"
                                label="Origine de la recette"
                                placeholder="Ex: francais, italien... "
                                fullWidth
                            />
                        </div>
                        <div className="mb-3 form-group">
                            <Field
                                as={TextField}
                                select
                                helperText={<ErrorMessage name="category"/>}
                                error={errors.category && props.touched.category}
                                fullWidth
                                label="Veuillez sélectionner une catégorie de recette"
                                name="category"
                            >
                                {
                                    categories && categories.map( category => (
                                        <MenuItem key={category.id} value={category.id}>{category.name}</MenuItem>))
                                }
                            </Field>
                        </div>
                        <div className="d-flex flex-row justify-content-between">
                            <div className="mb-3 form-group w-50">
                                <Field
                                    type="text"
                                    as={TextField}
                                    helperText={<ErrorMessage name="person"/>}
                                    error={errors.person && props.touched.person}
                                    name="person"
                                    label="Nombre de portions"
                                    placeholder="Ex: 1, 4... "
                                />
                            </div>
                            <div className="mb-3 form-group d-flex flex-colum align-items-end w-50">
                                <img src={currentPictureName} alt="Photo de la recette" className="w-25" />
                                <Input
                                    id="picture"
                                    name="picture"
                                    type="file"
                                    onChange={(e)=>UploadFile(e, setFieldValue)}
                                    /*onChange={(e) => {
                                        let reader = new FileReader();
                                        let file = e.target.files[0];
                                        if (file) {
                                            reader.onloadend = () => setCurrentPictureName(file.name);
                                            if (file.name !== currentPictureName) {
                                                reader.readAsDataURL(file);
                                                setCurrentPicture(reader);
                                    setFieldValue('picture',file);
                                }
                                }
                                    }}*/
                                />
                                <ErrorMessage className="text-danger" name="picture" component="div" />
                            </div>
                        </div>

                    </Card>
                    <div className="mb-3 form-group">
                        <Card className="p-3">
                            <h3>Ingrédients</h3>
                            <FieldArray  name='ingredients' id="ingredients">
                                {({ remove, push,...props }) => (
                                    <>
                                        {
                                            values.ingredients.map((ingredient, index)=>(
                                                <IngredientForm  key={index} index={index} setFieldValue={setFieldValue} remove={remove} errors={errors}/>
                                            ))
                                        }
                                        <button className="btn btn-secondary my-3" type='button' onClick={()=>push({name:'',quantity:'',unit:''})} >Ajouter un ingredient</button>
                                    </>
                                )}
                            </FieldArray>
                        </Card>
                    </div>
                    <div className="mb-3 form-group">
                        <Card className="p-3">
                            <h3>Étapes</h3>
                            <FieldArray  name='steps' id="steps">
                                {({ remove, push,move,...props }) => (
                                    <>
                                        {
                                            values.steps.map((step, index)=>(
                                                <StepForm key={index} index={index} remove={remove} errors={errors}/>
                                            ))
                                        }
                                        <button className="btn btn-secondary my-3" type='button' onClick={()=>push('')} >Ajouter une étape</button>
                                    </>
                                )}
                            </FieldArray>
                        </Card>
                    </div>

                    <button className="btn btn-primary align-self-end my-3" type="submit">Enregistrer la recette</button>
                   {/* <pre>{JSON.stringify({ values, errors }, null, 4)}</pre>*/}
                </Form>
            )}
        </Formik>
    );
}
export default RecipeForm;
const root = ReactDOM.createRoot (document.getElementById('react_form'));
root.render(
    <React.StrictMode>
        <RecipeForm />
    </React.StrictMode>
);