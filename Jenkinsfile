pipeline {
  agent { label 'raspberry' }

  environment {
    COMPOSE_BASE = "docker-compose.yml"
    COMPOSE_STAGING = "docker-compose.staging.yml"
    COMPOSE_PROD = "docker-compose.prod.yml"
  }

  stages {

    stage('Context') {
      steps {
        echo "Branch: ${env.BRANCH_NAME}"
        echo "PR: ${env.CHANGE_ID ?: 'no'}"
        echo "Target: ${env.CHANGE_TARGET ?: 'n/a'}"
      }
    }

    /* =========================
       PR → DEV : CI TEMPORAL
    ========================== */
    stage('CI - Testing Environment') {
      when { changeRequest target: 'dev' }
      steps {
        withCredentials([
          file(credentialsId: 'webcis-env-test', variable: 'ENV_FILE')
        ]) {
          sh '''
            echo "Preparing testing .env"
            rm -f .env || true
            cp ${ENV_FILE} .env

            echo "Stopping any previous CI stack"
            docker compose -f ${COMPOSE_BASE} down -v || true

            echo "Building images"
            docker compose -f ${COMPOSE_BASE} build

            echo "Starting CI environment"
            docker compose -f ${COMPOSE_BASE} up -d

            until [ "$(docker inspect --format='{{.State.Health.Status}}' webcis-backend)" = "healthy" ]; do
              echo "Waiting for app..."
              sleep 5
            done

            composer install --no-interaction --prefer-dist --optimize-autoloader

            docker compose exec -T app php artisan migrate --force

            rm -f .env
          '''
        }
      }
    }

    /* =========================
       DEV : STAGING DEPLOY
    ========================== */
    stage('Deploy Staging') {
      when { branch 'dev' }
      steps {
        withCredentials([
          file(credentialsId: 'webcis-env-staging', variable: 'ENV_FILE')
        ]) {
          sh '''
            echo "Preparing staging .env"
            rm -f .env || true
            cp ${ENV_FILE} .env

            echo "Stopping previous staging stack"
            docker compose \
              -f ${COMPOSE_STAGING} \
              down

            echo "Building staging image"
            docker compose \
              -f ${COMPOSE_STAGING} \
              build

            echo "Starting staging"
            docker compose \
              -f ${COMPOSE_STAGING} \
              up -d

            until [ "$(docker inspect --format='{{.State.Health.Status}}' webcis-backend)" = "healthy" ]; do
              echo "Waiting for app..."
              sleep 5
            done

            docker compose \
                -f ${COMPOSE_STAGING} \
                exec -T app php artisan config:cache

            docker compose \
                -f ${COMPOSE_STAGING} \
                exec -T app php artisan route:cache

            docker compose \
                -f ${COMPOSE_STAGING} \
                exec -T app php artisan view:cache

            docker compose \
              -f ${COMPOSE_STAGING} \
              exec -T app php artisan migrate --force

            rm -f .env
          '''
        }
      }
    }

    /* =========================
       MAIN : PRODUCTION DEPLOY
    ========================== */
    stage('Deploy Production') {
      when { branch 'main' }
      steps {
        withCredentials([
          file(credentialsId: 'webcis-env-production', variable: 'ENV_FILE')
        ]) {
          sh '''
            echo "Preparing production .env"
            rm -f .env || true
            cp ${ENV_FILE} .env

            echo "Stopping previous prod stack"
            docker compose \
              -f ${COMPOSE_PROD} \
              down

            echo "Building production image"
            docker compose \
              -f ${COMPOSE_PROD} \
              build

            echo "Starting production"
            docker compose \
              -f ${COMPOSE_PROD} \
              up -d

            until [ "$(docker inspect --format='{{.State.Health.Status}}' webcis-backend)" = "healthy" ]; do
              echo "Waiting for app..."
              sleep 5
            done

            docker compose \
                -f ${COMPOSE_PROD} \
                exec -T app php artisan config:cache

            docker compose \
                -f ${COMPOSE_PROD} \
                exec -T app php artisan route:cache

            docker compose \
                -f ${COMPOSE_PROD} \
                exec -T app php artisan view:cache

            docker compose \
              -f ${COMPOSE_PROD} \
              exec -T app php artisan migrate --force

            rm -f .env
          '''
        }
      }
    }
  }

  post {
    success {
      echo "Pipeline OK"
    }

    failure {
      echo "Pipeline FALLÓ"
    }
  }
}
