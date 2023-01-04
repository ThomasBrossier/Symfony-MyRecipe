import React, {useEffect, useState} from 'react';
import ReactDOM from 'react-dom/client';
import {ErrorMessage, Field, FieldArray, Form, Formik} from 'formik';
import IngredientForm from "./IngredientForm";
import {Box, Card, MenuItem, TextField} from "@mui/material";
import {initialValues, ValidationSchema} from "./ValidationForm";
import StepForm from "./stepForm";

const RecipeForm = () => {
    const [categories, setCategories] = useState([]);
    useEffect(()=>{
        fetch('https://127.0.0.1:8000/api/recipe/categories')
            .then(res=> res.json())
            .then(data => setCategories(data))
            .catch(err=> console.error(err));
    },[])
    return (
        <Formik
            onSubmit={( values , {resetForm}) => {
                let params = {
                    headers:{
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    method:'POST',
                    body:JSON.stringify(values)
                }
                fetch('https://127.0.0.1:8000/api/recipe/new',params)
                    .then(res => res.json())
                    .then(()=> resetForm(initialValues))
            }}
            initialValues={initialValues}
            validationSchema={ ValidationSchema }
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